<?php

class Guest_history_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('debugger');
	}

	public function get_view_history_with_term($guest_id, $term = NULL) {
		if(!is_set($term)) $term = 1/24;
		$now = time();
		$one = 24*60*60;
		$range = $term*$one;
		$query = $this->db
			->where('guest_id', $guest_id)
			->where('timestamp_int >=', $now - $range)
			->order_by("timestamp_int", "desc")
			->get('guest_view_history');
		
		if($query->num_rows() > 0) {
			return $query->result_array();
		}
	}

	public function add_view_history($guest_id, $article_id, $timestamp_int = NULL) {
		if(!isset($timestamp_int)) $timestamp_int = time();
		$data = array(
			'guest_id' => $guest_id,
			'article_id' => $article_id,
			'timestamp_int' => $timestamp_int,
		);
		$this->db->insert('guest_view_history', $data);
	}
	
	public function is_exist_view_with_term($guest_id, $article_id, $term = NULL) {
		if(!isset($term)) $term = 1/24;
		$now = time();
		$one = 24*60*60;
		$range = $term*$one;
		$query = $this->db
			->where('guest_id', $guest_id)
			->where('article_id', $article_id)
			->where('timestamp_int >=', $now - $range)
			->get('guest_view_history');
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}
}

?>