<?php

class Article_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->model('Tag_model');
		$this->load->library('minista_library');
	}

	// test OK
	function is_exists_article_id($article_id){
		$query = $this->db
			->where('id', $article_id)
			->where('delete_time =', 0)
			->get('article');
		if($query->num_rows() > 0) {return true;}
		return false;
	}

	// test OK
	function is_exists_item_id($item_id, $not_contain_deleted_item = TRUE){
		if($not_contain_deleted_item){
			$query = $this->db
				->where('id', $item_id)
				->where('delete_time =', 0)
				->get('item');
		}else{
			$query = $this->db
				->where('id', $item_id)
				->get('item');
		}
		if($query->num_rows() > 0) {return true;}
		return false;
	}

	function is_exists_item_id_with_deleted($item_id){
		$query = $this->db
			->where('id', $item_id)
			->get('item');
		if($query->num_rows() > 0) {return true;}
		return false;
	}
	
	function get_all_articles($sort_key = "timestamp_int", $order = "desc") {
		$query = $this->db->get('article');
		if($query->num_rows() > 0) {
			$data = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
			return $this->_order_by($data, $sort_key, $order);
		}
	}

	function get_articles_by_user_id($user_id, $sort_key = "timestamp_int", $order = "desc") {
		$query = $this->db
			->where('user_id', $user_id)
			->get('article');

		if($query->num_rows() > 0) {
			$data = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
			return $this->_order_by($data, $sort_key, $order);
		}
	}
	
	function get_recent_articles($category_id = 0, $limit = 5) {
		$this->db
			->from('article')
			->order_by("timestamp_int", "desc")
			->limit($limit);
		if($category_id > 0) {
			$this->db
				->join('categorizing', "article.id = categorizing.article_id", 'right')
				->where('categorizing.category_id', $category_id);
		}
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		}
	}
	
	/*なければ（or消えてれば）NULL*/
	function get_minista($id, $not_contain_deleted_item = TRUE) {
		$query = $this->db->get_where('article', array('id' => $id));
		
		if($query->num_rows() > 0) {
			$article = $query->row_array();
		} else {
			return NULL;
		}
		if($article['delete_time'] > 0) return NULL;
		
		$query = $this->db->get_where('item', array('article_id' => $id));
		if($query->num_rows() > 0) {
			$items = $query->result_array();
		} else {
			$items = NULL;
		}
		if($not_contain_deleted_item){
			$items = $this->minista_library->release_parent_has_element($items, 'delete_time', '>', 0);
		}
		
		return array('article' => $article, 'items' => $items);
	}
	
	function write_article($minista) {
		$title = $minista['t_target'] . $minista['t_do'] . $minista['t_how_many'] . $minista['t_what'];
		$query = $this->db->get_where('article', array('title' => $title));
		if($query->num_rows() > 0) {
			return FALSE;
		}
		$data = array(
			'user_id' => $minista['user_id'],
			'title' => $title,
			't_target' => $minista['t_target'],
			't_do' => $minista['t_do'],
			't_how_many' => $minista['t_how_many'],
			't_what' => $minista['t_what'],
			'limit' => count($minista['items']), //仮
			'regist_count' => count($minista['items']),
			'view_count' => 0,
			'favorite_count' => 0,
			'timestamp_int' => time(),
			'image_path' => $minista['image_path'],
			'image_author' => $minista['image_author'],
			'image_title' => $minista['image_title']
		);
		$this->db->insert('article', $data);
		$insert_id = $this->db->insert_id();
		
		/*念のため　insert_idが完全なら要らない*/
		$query = $this->db->get_where('article', array('id' => $insert_id));
		$article_id = -1;
		if($query->num_rows() > 0) {
			$row = $query->row();
			if($row->user_id == $minista['user_id'] && $row->title == $title) {
				$article_id = $row->id;
			}
		}
		if($article_id == -1) {
			$query = $this->db->get_where('article', array('user_id' => $minista['user_id'], 'title' => $title));
			if($query->num_rows() > 0) {
				$row = $query->row();
				$article_id = $row->id;
			} else {
				return FALSE;
			}
		}
		/*ここまで*/
		
		$items = $minista['items'];
		if(is_array($items)) {
			foreach($items as $item) {
				$data = array(
					'user_id' => $minista['user_id'],
					'article_id' => $article_id,
					'title' => $item['title'],
					'explanation' => $item['explanation'],
					'good' => 0,
					'bad' => 0,
					'stamp' => date(DATE_ATOM),
				);
				$this->add_item($data);
			}
		} else {
			return FALSE;
		}
		
		$category_id = $minista['category_id'];
		$this->Category_model->regist($category_id, $article_id);
		
		$tags = $minista['tags'];
		if(is_array($tags)) {
			for($i=0;$i<count($tags);$i++) {
				if(strlen($tags[$i]) > 0) {
					$flag = TRUE;
					for($j=0;$j<$i;$j++) {
						if($tags[$j] === $tags[$i]) {
							$flag = FALSE;
						}
					}
					if($flag) {
						$this->Tag_model->regist($tags[$i], $article_id);
					}
				}
			}
		} else {
			return FALSE;
		}
		
		return TRUE;
	}
	
	function get_active_articles($term, $now = NULL) {
		if(!isset($now)) {
			$now = time();
		}
		$one = 24*60*60;
		$range = $term*$one;
		$query = $this->db
			->select('article_id, COUNT(article_id) AS count')
			->where('timestamp_int <', $now)
			->where('timestamp_int >=', $now - $range)
			->group_by("article_id")
			->order_by("count", "desc")
			->limit(10)
			->get('vote_history');
		$i = 0;
		$data = NULL;
		foreach($query->result_array() as $row) {
			$data[$i] = $this->get_minista($row['article_id']);
			$i++;
		}
		return $data;
	}

	function get_user_id_by_item_id($item_id){
		$query = $this->db->get_where('item', array('id' => $item_id));

		if($query->num_rows() > 0) {
			$item = $query->row_array();
			return $item['user_id'];
		} else {
			return  NULL;
		}
	}

	function get_user_id_by_article_id($article_id){
		$query = $this->db->get_where('article', array('id' => $article_id));

		if($query->num_rows() > 0) {
			$article = $query->row_array();
			return $article['user_id'];
		} else {
			return  NULL;
		}
	}
	
	function update_view_count($article_id) {
		$this->db->set('view_count', 'view_count+1', FALSE);
		$this->db->where('id', $article_id);
		$this->db->update('article'); 
	}
	
	function update_favorite_count($article_id) {
		$this->db->set('favorite_count', 'favorite_count+1', FALSE);
		$this->db->where('id', $article_id);
		$this->db->update('article');
	}
	
	function update_vote($item_id, $is_good) {
		if($is_good) $this->db->set('good', 'good+1', FALSE);
		else $this->db->set('bad', 'bad+1', FALSE);
		$this->db->where('id', $item_id);
		$this->db->update('item');
	}

	function update_visible_item($item_id, $visible) {
		$this->db->set('visible', $visible, FALSE);
		$this->db->where('id', $item_id);
		$this->db->update('item');
	}

	function update_item($item_id) {
		$this->db->set('delete_time', 0);
		$this->db->set('delete_operator_id', 0);
		$this->db->where('id', $item_id);
		$this->db->update('item');
	}
	
	//タイトルなし：NULL　，重複：FALSE
	function add_item($data) {
		if(strlen($data['title']) > 0) {
			$query = $this->db->get_where('item', array('article_id' => $data['article_id'], 'title' => $data['title']));
			if($query->num_rows() > 0) {
				return FALSE;
			}
		} else {
			return NULL;
		}
		$this->db->insert('item', $data);
		$insert_id = $this->db->insert_id();
		// articleのt_how_manyを更新
		$this->_update_t_how_many_from_article($data['article_id']);
		return $insert_id;
	}
	
	function delete_article($article_id, $delete_operator_id) {
		$now = time();
		$this->db->set('delete_time', $now);
		$this->db->set('delete_operator_id', $delete_operator_id);
		$this->db->where('id', $article_id);
		$this->db->update('article');
	}
	
	function delete_item($item_id, $delete_operator_id) {
		$now = time();
		$this->db->set('delete_time', $now);
		$this->db->set('delete_operator_id', $delete_operator_id);
		$this->db->where('id', $item_id);
		$this->db->update('item');
	}
	
	function get_regist_count($article_id) {
		$query = $this->db
		  ->select('COUNT(article_id) AS count')
			->where('article_id', $article_id)
			->group_by("article_id")
			->get('item');
		$count = $query->row_array();
		return $count['count'];
	}
	
	private function _order_by($data, $key, $order = "desc") {
		$sort_key = array();
		foreach($data as $k => $v) {
			$sort_key[$k] = $v[$key];
		}
		if($order === "asc") {
			array_multisort($sort_key, SORT_ASC, $data);
		} else {
			array_multisort($sort_key, SORT_DESC, $data);
		}
		return $data;
	}

	public function get_count_items_by_article_id($article_id){
		$query = $this->db
		  ->select('COUNT(article_id) AS count')
			->where('article_id', $article_id)
			->where('delete_time', 0)
			->group_by("article_id")
			->get('item');
		$count = $query->row_array();
		if(empty($count) || !isset($count)){
			return 0;
		}else{
			return $count['count'];
		}
	}

	private function _update_t_how_many_from_article($article_id){
		$count = $this->get_count_items_by_article_id($article_id);
		$counter_suffix = $this->minista_library->get_counter_suffix($count);
		$t_how_many = $count . $counter_suffix;
		
		$this->db->set('t_how_many', $t_how_many);
		$this->db->where('id', $article_id);
		$this->db->update('article'); 
	}
}

?>