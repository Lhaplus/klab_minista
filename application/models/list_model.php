<?php
class List_model extends CI_Model{

  var $t_name = 'minista_list';

  public function __construct() {
    parent::__construct();
  }

  function get_all_entries() {
    $query = $this->db->get($this->t_name);
    return $query->result_array();
  }
}

?>