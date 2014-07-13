<?php

class History_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('ranking_time');
		$this->load->library('debugger');
	}
	public function get_recent_history_limit($user_id, $limit){
		$ret = $this->get_history($user_id);
		
		if($ret != null){
			if(count($ret) < $limit){$limit = count($ret);}
			$ret = array_slice($ret, 0, $limit);
			return $ret;
		}else{
			return null;
		}
	}
	

	public function get_history($user_id) { //適当なデータを入れたコマンドラインによるテストはパス済
		$view_history = $this->get_view_history($user_id);
		$vote_history = $this->get_vote_history($user_id);
		$favorite_history = $this->get_favorite_history($user_id);
		
		$view_index = 0;
		$favorite_index = 0;
		
		$view_max_index = count($view_history);
		$favorite_max_index = count($favorite_history);
		
		$data = array();
		
		//viewとfavoriteをtimestamp_intでマージソート
		$len = $view_max_index + $favorite_max_index;
		for($i=0;$i<$len;$i++) {
			if($view_index < $view_max_index) {
				$view_t = $view_history[$view_index]['timestamp_int'];
			} else {
				$view_t = 0;
			}
			if($favorite_index < $favorite_max_index) {
				$favorite_t = $favorite_history[$favorite_index]['timestamp_int'];
			} else {
				$favorite_t = 0;
			}
			
			if($view_t > $favorite_t) {
				$data[] = array(
					'type' => 'view',
					'article_id' => $view_history[$view_index]['article_id'],
					'timestamp_int' => $view_history[$view_index]['timestamp_int'],
				);
				$view_index++;
			} else {
				$data[] = array(
					'type' => 'favorite',
					'article_id' => $favorite_history[$favorite_index]['article_id'],
					'timestamp_int' => $favorite_history[$favorite_index]['timestamp_int'],
				);
				$favorite_index++;
			}
		}
		$this->debugger->set_debug_mode(TRUE);

		$data_index = 0;
		$vote_index = 0;
		
		$data_max_index = count($data);
		$vote_max_index = count($vote_history);
		
		$ret = array();
		
		//dataとvoteをtimestamp_intでマージソート
		$len = $data_max_index + $vote_max_index;
		for($i=0;$i<$len;$i++) {
			if($data_index < $data_max_index) {
				$data_t = $data[$data_index]['timestamp_int'];
			} else {
				$data_t = 0;
			}
			if($vote_index < $vote_max_index) {
				$vote_t = $vote_history[$vote_index]['timestamp_int'];
			} else {
				$vote_t = 0;
			}

			if($data_t > $vote_t) {
				$ret[] = array(
					'type' => $data[$data_index]['type'],
					'article_id' => $data[$data_index]['article_id'],
					'item_id' => NULL,
					'is_good' => NULL,
					'timestamp_int' => $data[$data_index]['timestamp_int']
				);
				$data_index++;
			} else {
				$ret[] = array(
					'type' => 'vote',
					'article_id' => $vote_history[$vote_index]['article_id'],
					'item_id' => $vote_history[$vote_index]['item_id'],
					'is_good' => $vote_history[$vote_index]['is_good'],
					'timestamp_int' => $vote_history[$vote_index]['timestamp_int']
				);
				$vote_index++;
			}
		}
		return $ret;
	}

	public function get_view_history($user_id) {
		$query = $this->db
			->where('user_id', $user_id)
			->order_by("timestamp_int", "desc")
			->get('view_history');
		return $query->result_array();
	}
	
	public function get_vote_history($user_id) {
		$query = $this->db
			->where('user_id', $user_id)
			->order_by("timestamp_int", "desc")
			->get('vote_history');
		return $query->result_array();
	}
	
	public function get_favorite_history($user_id) {
		$query = $this->db
			->where('user_id', $user_id)
			->order_by("timestamp_int", "desc")
			->get('favorite_history');
		return $query->result_array();
	}
	
	public function add_view_history($user_id, $article_id, $timestamp_int) {
		$data = array(
			'user_id' => $user_id,
			'article_id' => $article_id,
			'timestamp_int' => $timestamp_int,
		);
		$this->db->insert('view_history', $data);
	}
	
	public function add_vote_history($user_id, $article_id, $item_id, $is_good, $timestamp_int) {
		$data = array(
			'user_id' => $user_id,
			'article_id' => $article_id,
			'item_id' => $item_id,
			'is_good' => $is_good,
			'timestamp_int' => $timestamp_int,
		);
		$this->db->insert('vote_history', $data);
	}
	
	public function add_favorite_history($user_id, $article_id, $timestamp_int) {
		$data = array(
			'user_id' => $user_id,
			'article_id' => $article_id,
			'timestamp_int' => $timestamp_int,
		);
		$this->db->insert('favorite_history', $data);
	}
	

	public function is_exist_vote($user_id, $item_id) {
		$query = $this->db->get_where('vote_history', array('user_id' => $user_id, 'item_id' => $item_id));
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}
	
	public function is_exist_vote_with_term($user_id, $item_id, $term = 1) {
		$now = time();
		$one = 24*60*60;
		$range = ($now - $this->ranking_time->get_daily_ranking_time()) + ($term-1)*$one;
		$query = $this->db
			->where('user_id', $user_id)
			->where('item_id', $item_id)
			->where('timestamp_int >=', $now - $range)
			->get('vote_history');
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}
	
	public function is_exist_view_with_term($user_id, $article_id, $term = NULL) {
		if(!isset($term)) $term = 1/24;
		$now = time();
		$one = 24*60*60;
		$range = $term*$one;
		$query = $this->db
			->where('user_id', $user_id)
			->where('article_id', $article_id)
			->where('timestamp_int >=', $now - $range)
			->get('view_history');
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}
	
	//必要なくなった
	private function _get_category_id($article_id) {
		$query = $this->db->get_where('categorizing', array('article_id' => $article_id));
		if($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['category_id'];
		} else {
			return -1;
		}
	}
	
	//ここではいらない（形を変えてタイムライブラリに入れるかもしれないから残しておく）
	private function _compare_timestamp_int($timestamps) {
		if(is_array($timestamps)) {
			$min = 2147483647;
			for($i=0;$i<count($timestamps);$i++) {
				if(is_numeric($timestamps[$i])) {
					if($timestamps[$i] < $min) {
						$min = $timestamps[$i];
						$ret = $i;
					}
				} else {
					return -1;
				}
			}
			return $ret;
		} else {
			return -2;
		}
	}
}

?>