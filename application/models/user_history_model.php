<?php
/*
|
| favorite_history
| view_history
| vote_history
|
*/
class User_history_model extends CI_Model
{
  public function __construct()
  {
    parent::__construct();
  }

  function get_all_histories_by_user_id($user_id)
  {
		$favorite_histories = $this->get_favorite_histories_by_user_id($user_id);
		$view_histories = $this->get_view_histories_by_user_id($user_id);
		$vote_histories = $this->get_vote_histories_by_user_id($user_id);
		foreach($favorite_histories as &$history){
			$history['type'] = '1';
		}
		foreach($view_histories as &$history){
			$history['type'] = '3';
		}
		foreach($vote_histories as &$history){
			$history['type'] = '2';
		}
		$query = array_merge($favorite_histories, $view_histories);
		$query = array_merge($query, $vote_histories);
		$times = array();
		foreach($query as $v){
			$times[] = $v['type'];
		}
		array_multisort($times, SORT_DESC, SORT_NUMERIC, $query);
		
    return $query;
  }
  function get_favorite_histories_by_user_id($user_id)
  {
    $this->db->select('article_id');
		$this->db->from('favorite_history');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		
    return $query->result_array();
  }
  function get_view_histories_by_user_id($user_id)
  {

    $this->db->select('article_id');
		$this->db->from('view_history');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
			
    return $query->result_array();
  }
  function get_vote_histories_by_user_id($user_id)
  {
    $this->db->select('article_id, item_id, is_good');
		$this->db->from('vote_history');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		
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