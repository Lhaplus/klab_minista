<?php

class Category_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	function get_all_categories() {
		$query = $this->db->get('category');
		
		if($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
	
	function regist($category_id, $article_id) {
		$data = array(
			'category_id' => $category_id,
			'article_id' => $article_id,
		);
		$this->db->insert('categorizing', $data);
		
		$this->db->where('id', $category_id);
		$this->db->set('regist_count', 'regist_count+1', FALSE);
		$this->db->update('category');
	}
	
	function unregist($category_id, $article_id) {
		$data = array(
			'category_id' => category_id,
			'article_id' => article_id,
		);
		$this->db->delete('categorizing', $data);
		
		$this->db->where('id', $category_id);
		$this->db->set('regist_count', 'regist_count-1', FALSE);
		$this->db->update('category');
	}
	
	/*管理用*/
	function add_category($name) {
		$data = array (
			'name' => $name,
			'regist_count' => 0,
		);
			
		$this->db->insert('category', $data);
	}
	
	function delete_category($id) {
		$this->db->delete('category', array('id' => $id));
	}
}

?>