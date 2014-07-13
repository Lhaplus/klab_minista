<?php

class Search_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->library('minista_library');
	}
	
	public function search_by_keyword($keyword, $category_id = 0, $sort_key = "view_count", $order = "desc") {
		$keyword = $this->_keyword($keyword);
		$this->db
			->from('article')
			->where("MATCH (article.title) AGAINST (\"${keyword}\" IN BOOLEAN MODE)", NULL, FALSE)
			->order_by($sort_key, $order);
		if($category_id > 0) {
			$this->db
				->join('categorizing', "categorizing.article_id = article.id")
				->where('categorizing.category_id', $category_id);
		}
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			return $this->minista_library->release_parent_has_element($query->result_array(), 'delete_time', '>', 0);
		}
	}
	
	public function search_by_category($category_id, $sort_key = "timestamp_int", $order = "desc") {
		$query = $this->db->get_where('categorizing', array('category_id' => $category_id));
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			for($i=0;$i<count($data);$i++) {
				$query = $this->db->get_where('article', array('id' => $data[$i]['article_id']));
				$data[$i] = $query->row_array();
			}
			return $this->_order_by($data, $sort_key, $order);
		} else {
			return NULL;
		}
	}
	
	public function search_by_tag($tag_id, $sort_key = "view_count", $order = "desc") {
		$query = $this->db->get_where('tagging', array('tag_id' => $tag_id));
		if($query->num_rows() > 0) {
			$data = $query->result_array();
			for($i=0;$i<count($data);$i++) {
				$query = $this->db->get_where('article', array('id' => $data[$i]['article_id']));
				$data[$i] = $query->row_array();
			}
			return $this->_order_by($data, $sort_key, $order);
		} else {
			return NULL;
		}
	}
	
	public function search_by_tag_name($tag_name, $perfectly = FALSE, $sort_key = "view_count", $order = "desc") {
		$keyword = $this->_keyword($tag_name);
		if($perfectly) {
			$query = $this->db->get_where('tag', array('name' => $tag_name));
		} else {
			$query = $this->db
				->where("MATCH (name) AGAINST (\"${keyword}\" IN BOOLEAN MODE)", NULL, FALSE)
				->get('tag');
		}
		$result = $query->result_array();
		if(count($result) > 0) {
			$i = 0;
			foreach($result as $r) {
				$tag_id[$i] = $r['id'];
				$i++;
			}
		} else {
			return NULL;
		}
		
		$data = array();
		foreach($tag_id as $t_id) {
			$query = $this->db->get_where('tagging', array('tag_id' => $t_id));
			if($query->num_rows() > 0) {
				$tagging = $query->result_array();
				foreach($tagging as $ting) {
					$query = $this->db->get_where('article', array('id' => $ting['article_id']));
					$data[] = $query->row_array();
				}
			}
		}
		if(count($data) > 0) {
			return $this->_order_by($data, $sort_key, $order);
		} else {
			return NULL;
		}
	}
	
	public function log_keyword($user_id, $keyword) {
		$this->db->insert('search_keyword_log', array('user_id' => $user_id, 'keyword' => $keyword));
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
	
	private function _keyword($keyword) {
		$keyword = str_replace(array("\"", "(", ")", "'", "<", ">", "\\", "*", "+", "-"), " ", $keyword);
		$tmp = mb_split("\s+|　+|\t+", $keyword);
		$ret = '';
		foreach($tmp as $s) {
			if(strlen($s) > 0) {
				$ret = $ret."+".$s." ";
			}
		}
		return substr($ret,0,strlen($ret)-1);
	}
}

?>