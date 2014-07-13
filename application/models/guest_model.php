<?php
class Guest_model extends CI_Model {
	public function __construct() {
		parent::__construct();
	}

	public function get_id($ip_address) {
		$query = $this->db->get_where('guest', array('ip_address' => $ip_address));
		if($query->num_rows() > 0) {
			$row = $query->row_array();
			return $row['id'];
		}
	}

	public function is_exist($ip_address) {
		$query = $this->db->get_where('guest', array('ip_address' => $ip_address));
		if($query->num_rows() > 0) return TRUE;
		else return FALSE;
	}

	public function regist_guest($ip_address, $user_agent, $first_article_id, $timestamp_int = NULL) {
		if(!isset($timestamp_int)) $timestamp_int = time();
		$data = array(
			'ip_address' => $ip_address,
			'user_agent' => $user_agent,
			'first_article_id' => $first_article_id,
			'timestamp_int' => $timestamp_int
		);
		if(!$this->is_exist($ip_address)) $this->db->insert('guest', $data);
	}
}

?>