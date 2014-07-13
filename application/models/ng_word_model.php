<?php

class Ng_word_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	function get_all_ng_word(){
		$query = $this->db->get('ng_word');
		if($query->num_rows() > 0) {
			$articles = $query->result_array();
		} else {
			$articles = NULL;
		}
		return $articles;
	}
}


?>