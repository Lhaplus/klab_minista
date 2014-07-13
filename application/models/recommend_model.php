<?php

class Recommend_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('ranking_time');
		$this->load->model('Article_model');
	}
	
	public function add_recommend_map($user_id, $from_article_id, $to_article_id, $time = NULL) {
		if(!isset($time)) {
			$time = time();
		}
		$data = array(
			'user_id' => $user_id,
			'from_article_id' => $from_article_id,
			'to_article_id' => $to_article_id,
			'timestamp_int' => $time,
		);
		$this->db->insert('recommend_map', $data);
	}
	
	public function get_recommend($from_article_id, $term) {
		$now = time();
		$one = 24*60*60;
		$range = $term*$one;
		/*
		SELECT to_article_id, COUNT(to_article_id) AS count
		FROM recommend_map
		WHERE from_article_id = {$from_article_id} AND timestamp_int < {$now} AND timestamp_int >= {$now - $range}
		ORDER BY count DESC
		LIMIT 5
		*/
		$query = $this->db
			->select('to_article_id, COUNT(to_article_id) AS count')
			->where('from_article_id =', $from_article_id)
			->where('timestamp_int <', $now)
			->where('timestamp_int >=', $now - $range)
			->group_by("to_article_id")
			->order_by("count", "desc")
			->limit(5)
			->get('recommend_map');
		
		if($query->num_rows() > 0) {
			$result = $query->result_array();
			$articles = array();
			foreach($result as $row) {
				$minista = $this->Article_model->get_minista($row['to_article_id']);
				if(isset($minista)) $articles[] = $minista['article'];
			}
			return $articles;
		}
	}
}

?>