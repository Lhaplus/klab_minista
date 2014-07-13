<?php

class Ranking_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('ranking_time');
		$this->load->model('Article_model');
	}
	
	function create_view_ranking($term, $now = NULL, $category_id = 0) {
		if(!isset($now)) {
			$now = $this->ranking_time->get_create_time_of_today();
		}
		$one = 24*60*60;
		$range = $term*$one;
		/* 古い
		SELECT article_id, COUNT(article_id) AS count
		FROM view_history
		WHERE timestamp_int < {$now} AND timestamp_int >= {$now - $range}
		GROUP BY article_id
		ORDER BY count DESC
		LIMIT 100
		*/
		$this->db
			->select('view_history.article_id, COUNT(view_history.article_id) AS count')
			->where('timestamp_int <', $now)
			->where('timestamp_int >=', $now - $range);
		//if($category_id > 0) $this->db->where('category_id', $category_id);
		if($category_id > 0) {
			$this->db
				->join('categorizing', "categorizing.article_id = view_history.article_id")
				->where('categorizing.category_id', $category_id);
		}
		$query = $this->db
			->group_by("view_history.article_id")
			->order_by("count", "desc")
			->limit(100)
			->get('view_history');
		
		foreach($query->result_array() as $row) {
			$data = array(
				'article_id' => $row['article_id'],
				'category_id' => $category_id,
				'point' => $row['count'],
				'term' => $term,
				'timestamp_int' => $now,
			);
			$this->db->insert('view_ranking', $data);
		}
		$data = array(
			'article_id' => 0,
			'category_id' => $category_id,
			'point' => 0,
			'term' => $term,
			'timestamp_int' => $now,
		);
		$this->db->insert('view_ranking', $data);
	}
	
	function create_vote_ranking($term, $now = NULL) {
		if(!isset($now)) {
			$now = $this->ranking_time->get_create_time_of_hour();
		}
		
	}
	
	function create_favorite_ranking($term, $now = NULL, $category_id = 0) {
		if(!isset($now)) {
			$now = $this->ranking_time->get_create_time_of_today();
		}
		$one = 24*60*60;
		$range = $term*$one;
		$this->db
			->select('favorite_history.article_id, COUNT(favorite_history.article_id) AS count')
			->where('timestamp_int <', $now)
			->where('timestamp_int >=', $now - $range);
		//if($category_id > 0) $this->db->where('category_id', $category_id);
		if($category_id > 0) {
			$this->db
				->join('categorizing', "categorizing.article_id = favorite_history.article_id")
				->where('categorizing.category_id', $category_id);
		}
		$query = $this->db
			->group_by("favorite_history.article_id")
			->order_by("count", "desc")
			->limit(100)
			->get('favorite_history');
		
		foreach($query->result_array() as $row) {
			$data = array(
				'article_id' => $row['article_id'],
				'category_id' => $category_id,
				'point' => $row['count'],
				'term' => $term,
				'timestamp_int' => $now,
			);
			$this->db->insert('favorite_ranking', $data);
		}
		$data = array(
			'article_id' => 0,
			'category_id' => $category_id,
			'point' => 0,
			'term' => $term,
			'timestamp_int' => $now,
		);
		$this->db->insert('favorite_ranking', $data);
	}
	
	function get_view_ranking($term, $timestamp_int = NULL, $category_id = 0) {
		if(!isset($timestamp_int)) {
			$timestamp_int = $this->ranking_time->get_daily_ranking_time();
		}
		$query = $this->db->get_where('view_ranking', array('term' => $term,'category_id' => $category_id, 'timestamp_int' => $timestamp_int));
		$check = FALSE;
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			for($i=0;$i<count($data);$i++) {
				if($data[$i]['article_id'] == 0 && $data[$i]['point'] == 0) {
					$check = TRUE;
					unset($data[$i]);
					break;
				}
				$article = $this->Article_model->get_minista($data[$i]['article_id']);
				$title = $article['article']['t_target']."最低限".$article['article']['t_do'].$article['article']['t_how_many'].$article['article']['t_what'];
				$data[$i]['title'] = $title;
			}
			if($check) {
				return $data;
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}
	
	function get_favorite_ranking($term, $timestamp_int = NULL, $category_id = 0) {
		if(!isset($timestamp_int)) {
			$timestamp_int = $this->ranking_time->get_daily_ranking_time();
		}
		$query = $this->db->get_where('favorite_ranking', array('term' => $term, 'category_id' => $category_id, 'timestamp_int' => $timestamp_int));
		$check = FALSE;
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			for($i=0;$i<count($data);$i++) {
				if($data[$i]['article_id'] == 0 && $data[$i]['point'] == 0) {
					$check = TRUE;
					unset($data[$i]);
					break;
				}
				$article = $this->Article_model->get_minista($data[$i]['article_id']);
				$title = $article['article']['t_target']."最低限".$article['article']['t_do'].$article['article']['t_how_many'].$article['article']['t_what'];
				$data[$i]['title'] = $title;
			}
			if($check) {
				return $data;
			} else {
				return NULL;
			}
		} else {
			return NULL;
		}
	}
	
	function get_tag_ranking($limit = 100) {
		$query = $this->db->order_by('regist_count', 'desc')->limit($limit)->get('tag');
		if($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
}

?>