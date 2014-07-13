<?php

class My_minista_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('minista_library');
	}

	// test [OK]
	function is_exists_folder_id_from_article_folder($folder_id){
		$query = $this->db
			->where('id', $folder_id)
			->where('delete_time =', 0)
			->get('article_folder');
		if($query->num_rows() > 0) {return true;}
		return false;
	}

	function is_exists_article_id_from_my_article($article_id){
		$query = $this->db
			->where('id', $article_id)
			->where('delete_time =', 0)
			->get('my_article');
		if($query->num_rows() > 0) {return true;}
		return false;
	}

	function is_exists_item_id_from_my_item($article_id, $item_id){
		$query = $this->db
			->where('id', $item_id)
			->where('my_article_id', $article_id)
			->where('delete_time =', 0)
			->get('my_item');
		if($query->num_rows() > 0) {return true;}
		return false;
	}

	function get_user_all_folders($user_id){
		$query = $this->db->get_where('article_folder', array('user_id' => $user_id));

		if($query->num_rows() > 0) {

			$folders = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		} else {
			$folders = NULL;
		}
		return $folders;
	}

	function get_user_all_articles($user_id, $folder_id){
		$query = $this->db->get_where('my_article', array('user_id' => $user_id, 'article_folder_id' => $folder_id));

		if($query->num_rows() > 0) {
			$articles = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		} else {
			$articles = NULL;
		}
		return $articles;
	}

	function get_user_all_items($user_id, $article_id){
		$query = $this->db->get_where('my_item', array('user_id' => $user_id, 'my_article_id' => $article_id));

		if($query->num_rows() > 0) {
			$items = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		} else {
			$items = NULL;
		}
		return $items;
	}

	function get_user_article($user_id, $article_id){
		$query = $this->db->get_where('my_article', array('user_id' => $user_id, 'id' => $article_id));

		if($query->num_rows() > 0) {
			$article = $query->row_array();
		} else {
			$article = NULL;
		}
		return $article;
	}
	function get_user_id_my_minista_folder($folder_id){
		$query = $this->db->get_where('article_folder', array('id' => $folder_id));

		if($query->num_rows() > 0) {
			$folder = $query->row_array();
			return $folder['user_id'];
		} else {
			return  NULL;
		}
	}
	function get_user_id_my_minista_article($article_id){
		$query = $this->db->get_where('my_article', array('id' => $article_id));

		if($query->num_rows() > 0) {
			$article = $query->row_array();
			return $article['user_id'];
		} else {
			return  NULL;
		}
	}
	function get_user_id_my_minista_item($item_id){
		$query = $this->db->get_where('my_item', array('id' => $item_id));

		if($query->num_rows() > 0) {
			$item = $query->row_array();
			return $item['user_id'];
		} else {
			return  NULL;
		}
	}

	// test [OK]
	function get_folder_current_count($user_id){
		$query = $this->db
		  ->select('COUNT(user_id) AS count')
			->where('user_id', $user_id)
			->where('delete_time =', 0)
			->group_by("user_id")
			->get('article_folder');
		$count = $query->row_array();
		if(empty($count)){
			return 0;
		}else{
			return $count['count'];
		}
	}

	function get_article_max_count($user_id, $folder_id){
		$query = $this->db
		  ->select('article_max_count')
			->where('id', $folder_id)
			->get('article_folder');
		$max_count = $query->row_array();
		return $max_count['article_max_count'];
	}

	function get_article_current_count($user_id, $folder_id){
		$query = $this->db
		  ->select('COUNT(user_id) AS count')
			->where('user_id', $user_id)
			->where('article_folder_id', $folder_id)
			->where('delete_time', 0)
			->group_by('user_id')
			->get('my_article');
		$count = $query->row_array();
		if(empty($count)){
			return 0;
		}else{
			return $count['count'];
		}
	}

	function get_my_minista_item_current_count($user_id, $article_id){
		$query = $this->db
		  ->select('COUNT(user_id) AS count')
			->where('user_id', $user_id)
			->where('my_article_id', $article_id)
			->where('delete_time >', 0)
			->group_by("user_id")
			->get('my_item');
		$count = $query->row_array();
		if(empty($count) || !isset($count)){
			return 0;
		}else{
			return $count['count'];
		}
	}
	
	function add_my_minista_folder($user_id, $name){
		$data = array(
			'user_id' => $user_id,
			'name' => $name,
			'sort_state' => 0
		);
		
    $this->db->insert("article_folder", $data);
		$insert_id = $this->db->insert_id();
		return $insert_id;
	}

	function add_my_minista($user_id, $folder_id, $param){
		$article = $param['article'];
		$items = $param['items'];
		if($article != NULL){
			$data = array(
				'user_id' => $user_id,
				'article_id' => $article['id'],
				'article_folder_id' => $folder_id,
				't_target' => $article['t_target'],
				't_do' => $article['t_do'],
				't_how_many' => $article['t_how_many'],
				't_what' => $article['t_what'],
				'regist_count' => $article['regist_count'],
				'timestamp_int' => $article['timestamp_int'],
				'image_path' => $article['image_path'],
				'image_title' => $article['image_title'],
				'image_author' => $article['image_author'],
			);
			$this->db->insert("my_article", $data);
			$insert_id = $this->db->insert_id();
			if($items != NULL){
				foreach($items as $item) {
					$data = array(
						'user_id' => $user_id,
						'my_article_id' => $insert_id,
						'title' => $item['title'],
						'stamp' => $item['stamp']
					);
					$this->db->insert("my_item", $data);
				}
			}
			$this->_update_folder_count($folder_id, '+');
			return $insert_id;
		}
		return NULL;
	}


	function add_my_minista_item($user_id, $article_id, $title, $explanation){
		$data = array(
			'user_id' => $user_id,
			'my_article_id' => $article_id,
			'title' => $title,
			'explanation' => $explanation
		);
		
    $this->db->insert("my_item", $data);
		$insert_id = $this->db->insert_id();
		$t_how_many = $this->_update_t_how_many($article_id);
		return $insert_id;
	}


	function delete_my_minista_folder($folder_id, $delete_operator_id = 0){
		$articles = NULL;
		$items = NULL;
		$now = time();

		// articleを探す
		$query = $this->db->get_where('my_article', array('article_folder_id' => $folder_id));
		if($query->num_rows() > 0) {
			$articles = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);

		} else {
			$articles = NULL;
		}
		// itemとarticleを削除する
		if($articles != null){
			foreach($articles as $key => $article){
				$this->db->set('delete_time', $now);
				$this->db->set('delete_operator_id', $delete_operator_id);
				$this->db->where('my_article_id', $article['id']);
				$this->db->update('my_item');
				$this->db->set('delete_time', $now);
				$this->db->set('delete_operator_id', $delete_operator_id);
				$this->db->where('id', $article['id']);
				$this->db->update('my_article');
			}
		}
		// folder を削除する
		$this->db->set('delete_time', $now);
		$this->db->set('delete_operator_id', $delete_operator_id);
		$this->db->where('id', $folder_id);
		$this->db->update('article_folder');
	}

	function delete_my_minista_article($article_id, $folder_id, $delete_operator_id = 0){
		$items = NULL;
		$now = time();

		// itemを探す
		$query = $this->db->get_where('my_item', array('my_article_id' => $article_id));
		if($query->num_rows() > 0) {
			$items = $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		} else {
			$items = NULL;
		}
		// item削除する
		if($items != null){
			foreach($items as $key => $item){
				$this->delete_my_minista_item($article_id, $item['id']);
			}
		}
		//  articleを削除する
		$this->_update_folder_count($folder_id, "-");

		$this->db->set('delete_time', $now);
		$this->db->set('delete_operator_id', $delete_operator_id);
		$this->db->where('id', $article_id);
		$this->db->update('my_article');
	}

	function delete_my_minista_item($article_id, $item_id, $delete_operator_id = 0){
		$now = time();
		$this->db->set('delete_time', $now);
		$this->db->set('delete_operator_id', $delete_operator_id);
		$this->db->where('id', $item_id);
		$this->db->update('my_item');

		$this->_update_t_how_many($article_id);
	}

	function update_my_minista_folder_name($folder_id, $new_folder_name){
		$this->db->where('id', $folder_id);
		$this->db->set('name', $new_folder_name);
		$this->db->update('article_folder');
	}

	/*
	  private function
	 */
	private function _get_count_items_by_article_id($my_article_id){
		$query = $this->db
		  ->select('COUNT(my_article_id) AS count')
			->where('my_article_id', $my_article_id)
			->where('delete_time', 0)
			->group_by("my_article_id")
			->get('my_item');
		$count = $query->row_array();
		if(empty($count) || !isset($count)){
			return 0;
		}else{
			return $count['count'];
		}
	}

	private function _update_t_how_many($article_id){
		// item数を取得する
		$count = $this->_get_count_items_by_article_id($article_id);
		$counter_suffix = $this->minista_library->get_counter_suffix($count);
		$t_how_many = $count . $counter_suffix;

		$this->db->where('id', $article_id);
		$this->db->set('t_how_many', $t_how_many);
		$this->db->update('my_article');
		return $t_how_many;
	}

	private function _update_folder_count($folder_id, $pn_flg){
		if($pn_flg === '+'){
			$this->db->set('regist_count', 'regist_count + 1', FALSE);
		}else if($pn_flg === '-'){
			$this->db->set('regist_count', 'regist_count - 1', FALSE);
		}else{
			return NULL;
		}
		$this->db->where('id', $folder_id);
		$this->db->update('article_folder');
	}
}


?>