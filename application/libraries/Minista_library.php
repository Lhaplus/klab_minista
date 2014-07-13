<?php

define('MINISTA_IS_OK', 0);
define('MINISTA_IS_ERROR', 1);

class Minista_library {

	const SUBDIR = 'minista/';
	private $path = 'minista/';

	function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->lang->load('minista');
		$this->ci->load->model('History_model');
		$this->ci->load->model('Article_model');
		$this->ci->load->model('Category_model');
		$this->ci->load->model('Ng_word_model');
		$this->ci->load->model('tank_auth/users', 'user');
	}

	public function get_recent_articles($category_id = 0) {
		return $this->ci->Article_model->get_recent_articles($category_id);
	}
	
	public function get_categories() {
		return $this->ci->Category_model->get_all_categories();
	}
	
	/* Release parent specified $element that fulfill following conditional expression, '$element $condition $target'.
	 * Retuen filtered data.
	 * argument examples 
	 * ($data, 'name', '==', 'ABC')
	 * ($data, 'id', '>', 1)
	 */
	function release_parent_has_element($data, $element, $condition, $target) {
		if(is_array($data) && is_string($element) && (is_numeric($target) || is_string($target)) && 
			($condition === '==' || $condition === '===' || $condition === '!=' || $condition === '<>' || $condition === '!==' ||
			 $condition === '<' || $condition === '>' || $condition === '<=' || $condition === '>=')) {
			foreach($data as $key => $value) {
				if(is_array($value)) {
					$data[$key] = $this->release_parent_has_element($data[$key], $element, $condition, $target);
				} else {
					$t = $target;
					if(is_string($target)) $target = "'".$target."'";
					if($key == $element && eval('return $value'.$condition.$target.';')) {
						unset($data);
					}
					$target =$t;
				}
			}
		}
		if(isset($data)) return array_filter($data, function($var) {return isset($var) & count($var) > 0;});
		else return NULL;
	}
	
	/*
	 * @param
	 * return articles_add_username
	 */
	public function add_username_to_all_articles(&$articles){
		if(is_array($articles)){
			foreach($articles as &$article){
				if($article != NULL){
					$this->add_username_to_article($article);
				}
			}
		}
	}
	public function add_username_to_article(&$article) {

		$user_name = $this->ci->user->get_user_name_by_id($article['user_id']);
		if($user_name == NULL){
			$article += array('user_name' => "no name");
		}else{
			$article += array('user_name' => $user_name);
		}
	}

	/*
	 * @param
	 * return items_add_vote_flg
	 */
	public function add_vote_flg_to_items(&$items){
		if(is_array($items)){
			foreach($items as &$item){
				$this->add_vote_flg_to_item($item);
			}
		}
	}

	public function add_vote_flg_to_item(&$item){
		$user_id  = $this->ci->tank_auth->get_user_id();
		$vote_flg = $this->ci->History_model->is_exist_vote_with_term($user_id, $item["id"]);
		if($vote_flg == NULL){
			$item += array('vote_flg' => FALSE);
		}else{
			$item += array('vote_flg' => $vote_flg);
		}
	}

	/*
	|---------------------------------------------------------------------
	| ajax function
	|---------------------------------------------------------------------
	*/

	public function minista_ajax($return_value){
		$options = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_PRETTY_PRINT;
		header('X-Content-Type-Options: nosniff');
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($return_value, $options);
	}

	public function ajax_return_error_message($error_message){
		$return_value = array(
			'state' => MINISTA_IS_ERROR,
			'error_message' => $error_message
		);
		$this->minista_ajax($return_value);
	}

	public function is_error_not_ajax_request_and_return_message(){
		$head_path = $this->path . 'general_message_head';
		$body_path = $this->path . 'general_message';
		if(!$this->ci->input->is_ajax_request()) {
			$error_message = $this->ci->lang->line('ajax_error_minista_invalid_request');
			$data = array(
				'h1_title' => 'エラー',
				'message' => $error_message
			);
			$this->ci->template->load('sub_page', $head_path, $body_path, $data);
			return true;
		}else{
			return false;
		}
	}

	public function is_error_no_login_and_return_ajax_message(){
		if (!$this->ci->tank_auth->is_logged_in()){
			$error_message = $this->ci->lang->line('ajax_error_minista_no_login');
			$this->ajax_return_error_message($error_message);
			return true;
		}
		return false;
	}

	public function is_error_invalid_user_and_return_ajax_message($user_id){
		$auth_user_id = $this->ci->tank_auth->get_user_id();
		if ($user_id !== $auth_user_id){
			$error_message = $this->ci->lang->line('ajax_error_minista_invalid_user');
			$this->ajax_return_error_message($error_message);
			return true;
		}
		return false;
	}

	/*
	|---------------------------------------------------------------------
	| 助数詞
	|---------------------------------------------------------------------
	*/
	public function get_counter_suffix($count){
		if($count > 10){
			return 'この';
		}else{
			return 'つの';
		}
	}

	/*
	|---------------------------------------------------------------------
	| 不正な入力をチェック
	|---------------------------------------------------------------------
	*/

	public function is_contain_ng_word($str){
		$split_words = mecab_split($str);
		$split_word = $str;
		// url
		if(preg_match('/http/i', $str)){return TRUE;}
		foreach($split_words as $split_word){
      $ng_words = $this->ci->Ng_word_model->get_all_ng_word();
      if($ng_words == NULL){return FALSE;}
			
      foreach($ng_words as $ng_word){
        if($ng_word['ng_word'] == $split_word){
          return TRUE;
        }
			}
    }
		
    return FALSE;
  }
}

?>

