<?php
class User_model extends CI_Model
{
  var $name = '';

  var $t_user = 'user';

  public function __construct()
  {
    parent::__construct();
  }

  function get_all_entries()
  {
    $query = $this->db->get($t_user);
    return $query->result_array();
  }


  function insert_entry($name)
  {
    $this->name = $name;
    $this->db->insert($t_user, $this);
  }

  function update_entry($id, $url)
  {
    $this->db->update($t_list, $url, array('id' => $id));
  }
}

?>