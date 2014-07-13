<?php

class Tag_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}
	
	function get_all_tags() {
		$query = $this->db->get('tag');
		
		if($query->num_rows() > 0) {
			return $query->result_array();
		}
	}
	
	function get_tags($article_id) {
		$query = $this->db->get_where('tagging', array('article_id' => $article_id));
		if($query->num_rows() > 0) {
			$ret = $query->result_array();
			foreach($ret as &$data) {
				$query = $this->db->get_where('tag', array('id' => $data['tag_id']));
				if($query->num_rows() > 0) {
					$row = $query->row_array();
					$data['name'] = $row['name'];
				} else {
					return NULL;
				}
			}
			return $ret;
		}
	}
	
	function regist($tag_name, $article_id) {
		$query = $this->db->get_where('tag', array('name' => $tag_name));
		if($query->num_rows() > 0) {
			$row = $query->row();
			$tag_id = $row->id;
		} else {
			$tag_id = $this->add_tag($tag_name);
		}
		
		$data = array(
			'tag_id' => $tag_id,
			'article_id' => $article_id,
		);
		$this->db->insert('tagging', $data);
		
		$this->db->where('id', $tag_id);
		$this->db->set('regist_count', 'regist_count+1', FALSE);
		$this->db->update('tag');
	}
	
	function unregist($tag_id, $article_id) {
		$data = array(
			'tag_id' => $tag_id,
			'article_id' => $article_id,
		);
		$this->db->delete('tagging', $data);
		
		$this->db->where('id', $tag_id);
		$this->db->set('regist_count', 'regist_count-1', FALSE);
		$this->db->update('tag');
	}
	
	function add_tag($name) {
		$data = array (
			'name' => $name,
			'regist_count' => 0,
		);
			
		$this->db->insert('tag', $data);
		return $this->db->insert_id();
	}
	
	/*管理用*/
	function delete_tag($id) {
		$this->db->delete('tag', array('id' => $id));
	}
}
?>