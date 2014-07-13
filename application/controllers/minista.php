<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Minista extends CI_Controller {
	const SUBDIR = 'minista/';
	const MY_MINISTA_ITEM_MAX_COUNT = 30;
	const MINISTA_ITEM_MAX_COUNT    = 30;
	const MAX_TITLE_LENGTH          = 20;
	const MAX_EXPLANATION_LENGTH    = 100;
	const MAX_IMAGE_LENGTH          = 100;

	const PAGER_LIMIT = 10;

	const BAD  = 0;
	const GOOD = 1;

	private $path = 'minista/';

	public function __construct() {
		parent::__construct();
		$this->load->model('List_model', 'minista');
		$this->load->model('tank_auth/users', 'user');
		$this->load->model('History_model');
		$this->load->model('Article_model');
		$this->load->model('Ranking_model');
		$this->load->model('My_minista_model');
		$this->load->model('Recommend_model');
		$this->load->model('Search_model');
		$this->load->model('Category_model');
		$this->load->model('Recommend_model');
		$this->load->model('Tag_model');
		$this->load->model('Guest_model');
		$this->load->model('Guest_history_model');
		$this->load->library('minista_library');
		$this->load->library('ranking_time');
		$this->load->library('template');
		$this->load->library('device');
		$this->load->library('pager');
		$this->load->library('pagination');
		$this->load->library('tank_auth');
		$this->load->library('session');
		$this->load->library('form_validation', '', 'validation');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper('minista_helper');
		$this->lang->load('minista');

		$this->path = $this->device->get_sp_path() . Minista::SUBDIR;
		
	}

	/*
	|---------------------------------------------------------------------
	| Template index
	|---------------------------------------------------------------------
	*/
	public function index($offset = 0, $category = 'top') {
		$head_path = $this->path . 'index_head';
		$body_path = $this->path . 'index';
		
		$categories = $this->Category_model->get_all_categories();
		
		//$data['articles'] = $this->Article_model->get_all_articles();
		$popular_articles = $this->Ranking_model->get_view_ranking(1);
		
		$data['now_category'] = 0;
		foreach($categories as $c) {
			if($category === $c['name']) {
				$popular_articles = $this->Ranking_model->get_view_ranking(1,NULL,$c['id']);
				$data['now_category'] = $c['id'];
			}
		}
		
		$data['articles'] = array();
		if(isset($popular_articles)) {
			foreach($popular_articles as $article) {
				$row = $this->Article_model->get_minista($article['article_id']);
				if(isset($row)){
					$data['articles'][] = $row['article'];
				}
			}
		}
			
		$this->minista_library->add_username_to_all_articles($data['articles']);
		$base_url = base_url() . "minista/index";
		$total_rows = count($data['articles']);
		$limit = Minista::PAGER_LIMIT;
		
		$data['pager'] = $this->pager->create_pager($base_url,$total_rows,$limit,$offset,$data['articles']);
		
		//$data['active_articles'] = $this->Article_model->get_active_articles(1/24);
		
		$data['categories'] = $categories;
		
		$this->session->set_userdata('now_category', $data['now_category']);
		
		$this->template->load('base_index', $head_path, $body_path, $data);
	}

	public function ranking(){ //単体テスト待ち
		$head_path = $this->path . 'ranking_head';
		$body_path = $this->path . 'ranking';
		
		//daily
		$time = $this->ranking_time->get_daily_ranking_time();
		$view_daily = $this->Ranking_model->get_view_ranking(1, $time);
		$favorite_daily = $this->Ranking_model->get_favorite_ranking(1, $time);
		if($view_daily == NULL || $favorite_daily == NULL) {
			$time = $this->ranking_time->get_daily_ranking_time(1);
			$view_daily = $this->Ranking_model->get_view_ranking(1, $time);
			$favorite_daily = $this->Ranking_model->get_favorite_ranking(1, $time);
		}
		
		//weekly
		$time = $this->ranking_time->get_weekly_ranking_time();
		$view_weekly = $this->Ranking_model->get_view_ranking(7, $time);
		$favorite_weekly = $this->Ranking_model->get_favorite_ranking(7, $time);
		if($view_weekly == NULL || $favorite_weekly == NULL) {
			$time = $this->ranking_time->get_weekly_ranking_time(1);
			$view_weekly = $this->Ranking_model->get_view_ranking(7, $time);
			$favorite_weekly = $this->Ranking_model->get_favorite_ranking(7, $time);
		}
		
		
		//monthly
		$time = $this->ranking_time->get_monthly_ranking_time();
		$days = $this->ranking_time->get_month_length($time);
		$view_monthly = $this->Ranking_model->get_view_ranking($days, $time);
		$favorite_monthly = $this->Ranking_model->get_favorite_ranking($days, $time);
		if($view_monthly == NULL || $favorite_monthly == NULL) {
			$time = $this->ranking_time->get_monthly_ranking_time(1);
			$days = $this->ranking_time->get_month_length($time);;
			$view_monthly = $this->Ranking_model->get_view_ranking($days, $time);
			$favorite_monthly = $this->Ranking_model->get_favorite_ranking($days, $time);
		}
		
		
		$data = array(
			'daily' => array(
				'view' => $view_daily,
				'favorite' => $favorite_daily,
			),
			'weekly' => array(
				'view' => $view_weekly,
				'favorite' => $favorite_weekly,
			),
			'monthly' => array(
				'view' => $view_monthly,
				'favorite' => $favorite_monthly,
			),
		);
		
		$categories = $this->Category_model->get_all_categories();
		$data['categories'] = $categories;
		
		$this->template->load('base_index', $head_path, $body_path, $data);
	}
	
	public function batch_ranking() { //統合テスト待ち
		if($this->input->is_cli_request()) {
			$categories = $this->Category_model->get_all_categories();
			$this->Ranking_model->create_view_ranking(1);
			$this->Ranking_model->create_favorite_ranking(1);
			foreach($categories as $c) {
				$this->Ranking_model->create_view_ranking(1, NULL, $c['id']);
				$this->Ranking_model->create_favorite_ranking(1, NULL, $c['id']);
			}
			$week = date("w");
			$day = date("j");
			if($week == 1) { //月曜
				$this->Ranking_model->create_view_ranking(7);
				$this->Ranking_model->create_favorite_ranking(7);
				foreach($categories as $c) {
				$this->Ranking_model->create_view_ranking(7, NULL, $c['id']);
				$this->Ranking_model->create_favorite_ranking(7, NULL, $c['id']);
			}
			}
			if($day == 1) { //一日
				$days = $this->ranking_time->get_last_month_length();
				$this->Ranking_model->create_view_ranking($days);
				$this->Ranking_model->create_favorite_ranking($days);
				foreach($categories as $c) {
				$this->Ranking_model->create_view_ranking($days, NULL, $c['id']);
				$this->Ranking_model->create_favorite_ranking($days, NULL, $c['id']);
			}
			}
		} else {
			redirect('index');
		}
	}

	/*
	|---------------------------------------------------------------------
	| Template article page
	|---------------------------------------------------------------------
	*/
	 
	//状態：手つかず（無印）-> 設計中 -> 仕様確認中 -> 単体テスト待ち -> 統合テスト待ち -> β版
	
	//状態：単体テスト待ち
	public function article($id = 0) {
		$head_path = $this->path . 'article_head';
		$body_path = $this->path . 'article';

		// validation
		if(!$this->validation->is_natural_no_zero($id) ||
			 !$this->validation->required($id))
		{
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		$data = $this->Article_model->get_minista($id);
		if(!isset($data['article'])) {
			$this->general_message("エラー","見つからないか削除されました。");
			return;
		}
		
		$this->_update_view_count($id);

		// ログインユーザの場合：view_history更新，投稿ユーザか調べる
		$data['posted_user'] = FALSE;
		$user_id = 0;
		if($this->tank_auth->is_logged_in()) {
			$user_id = $this->tank_auth->get_user_id();
			$this->History_model->add_view_history($user_id, $id, time());
			if($user_id == $data['article']['user_id']){
				$data = $this->Article_model->get_minista($id, FALSE);
				$data['posted_user'] = TRUE;
			}
			$data['folders'] = $this->My_minista_model->get_user_all_folders($user_id);
		}
		$this->minista_library->add_vote_flg_to_items($data['items']);
		$this->minista_library->add_username_to_article($data['article']);

		$data['t_how_many'] = $this->Article_model->get_count_items_by_article_id($id);
		
		// recommend_map生成処理
		$last_article_id = $this->session->userdata('last_article_id');
		$footprint_time = $this->session->userdata('footprint_time');
		$now = time();
		if(isset($last_article_id, $footprint_time) && $last_article_id > 0) {
			if($now - $this->session->userdata('footprint_time') < 3600 && $last_article_id != $id && !$this->input->get('recommend')) {
				$this->Recommend_model->add_recommend_map($user_id, $last_article_id, $id);
			}
		}
		$this->session->set_userdata('last_article_id', $id);
		$this->session->set_userdata('footprint_time', $now);

		$data['recommend_articles'] = $this->Recommend_model->get_recommend($id, 7);
		$this->minista_library->add_username_to_all_articles($data['recommend_articles']);
		
		$data['tags'] = $this->Tag_model->get_tags($id);
		
		$this->template->load('article_page', $head_path, $body_path, $data);
	}
	
	private function _update_view_count($article_id) {
		if($this->tank_auth->is_logged_in()) {
			$user_id = $this->tank_auth->get_user_id();
			if(!$this->History_model->is_exist_view_with_term($user_id, $article_id)) {
				$this->Article_model->update_view_count($article_id);
			}
		} else {
			$ip_address = $this->session->userdata('ip_address');
			if($ip_address != FALSE) {
				if($this->Guest_model->is_exist($ip_address)) {
					$guest_id = $this->Guest_model->get_id($ip_address);
					if(!$this->Guest_history_model->is_exist_view_with_term($guest_id, $article_id)) {
						$this->Article_model->update_view_count($article_id);
					}
					$this->Guest_history_model->add_view_history($guest_id, $article_id);
				} else {
					$user_agent = $this->session->userdata('user_agent');
					$this->Guest_model->regist_guest($ip_address, $user_agent, $article_id);
				}
			}
		}
	}

	// test [OK]
	function switch_visible_article() {
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}

		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}

		// validation
		$this->validation->set_rules('item_id', 'item_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('visible', 'visible', 'required|alpha_numeric|greater_than[-1]|less_than[2]');

		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$item_id = $this->input->post('item_id');
			$visible = $this->input->post('visible');
			// item_id::user_idとtank_auth::userid比較
			$user_id = $this->Article_model->get_user_id_by_item_id($item_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}
			// item_idが存在するか確認
			if(!$this->Article_model->is_exists_item_id($item_id, FALSE)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			
			$this->Article_model->update_visible_item($item_id, $visible);
			if($visible == Minista::GOOD){
				$this->Article_model->update_item($item_id);
			}else{
				$user_id = $this->tank_auth->get_user_id();
				$this->Article_model->delete_item($item_id, $user_id);
			}
			$return_value = array(
				'state' => MINISTA_IS_OK,
				'visible' => $visible
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}

	public function write() { //状態：統合テスト待ち
		$head_path = $this->path . 'write_head';
		$body_path = $this->path . 'write';
		
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/auth/index');
		}
		
		$data = array(
			'categories' => $this->Category_model->get_all_categories(),
			'num_items' => 1
		);
		$this->template->load('base_index', $head_path, $body_path, $data);
	}
	
	public function write_form() { //状態：統合テスト待ち
		$head_path = $this->path . 'write_form_head';
		$body_path = $this->path . 'write_form';
		$head_error_path = $this->path . 'write_head';
		$body_error_path = $this->path . 'write';
		
		if(!$this->tank_auth->is_logged_in()) {
			redirect('/auth/index');
		}

		// validation
		$this->validation->set_rules('t_target', 'ターゲット', 'required|xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
		$this->validation->set_rules('t_do', '何を', 'required|xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
		$this->validation->set_rules('t_what', '何', 'required|xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
		$this->validation->set_rules('tags[]', 'タグ', 'xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
		$this->validation->set_rules('category', 'カテゴリ', 'required|is_natural_no_zero');
		$this->validation->set_rules('image_path', '画像パス', 'xss_clean|max_length['.Minista::MAX_IMAGE_LENGTH.']');
		$this->validation->set_rules('image_title', '画像タイトル', 'xss_clean|max_length['.Minista::MAX_IMAGE_LENGTH.']');
		$this->validation->set_rules('image_author', '画像著者', 'xss_clean|max_length['.Minista::MAX_IMAGE_LENGTH.']');

		$items_count = count($this->input->post('items'));
		for($i = 0; $i < $items_count; $i++){
			$this->validation->set_rules('items[' . $i . '][title]', 'リストタイトル', 'required|xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
			$this->validation->set_rules('items[' . $i . '][explanation]', 'リスト説明', 'xss_clean|max_length['.Minista::MAX_EXPLANATION_LENGTH.']');
		}

		$data = array(
			'categories' => $this->Category_model->get_all_categories(),
			'num_items' => $items_count,
			'error_message' => ''
		);

		if($items_count > Minista::MY_MINISTA_ITEM_MAX_COUNT){
			// error
			$this->template->load('base_index', $head_error_path, $body_error_path, $data);
		}

		if($this->validation->run() == FALSE){
			// error
			$this->template->load('base_index', $head_error_path, $body_error_path, $data);
		}else{
			// success
			$t_target = $this->input->post('t_target');
			$t_do = $this->input->post('t_do');
			$counter_suffix = $this->minista_library->get_counter_suffix($items_count);
			$t_how_many = $items_count . $counter_suffix;
			$t_what = $this->input->post('t_what');
			$category_id = $this->input->post('category');
			$image_path = $this->input->post('image_path');
			$image_title = $this->input->post('image_title');
			$image_author = $this->input->post('image_author');
			$items = $this->input->post('items');
			$tags = $this->input->post('tags');

			// titleとexplanationの文法チェック
			if($this->minista_library->is_contain_ng_word($t_target)){
				$data['error_message'] = $t_target . 'が不正です';
				$this->template->load('base_index', $head_error_path, $body_error_path, $data);
				return;
			}
			if($this->minista_library->is_contain_ng_word($t_do)){
				$data['error_message'] = $t_do . 'が不正です';
				$this->template->load('base_index', $head_error_path, $body_error_path, $data);
				return;
			}
			if($this->minista_library->is_contain_ng_word($t_what)){
				$data['error_message'] = $t_what . 'が不正です';
				$this->template->load('base_index', $head_error_path, $body_error_path, $data);
				return;
			}
			foreach($items as $item){
				if($this->minista_library->is_contain_ng_word($item['title'])){
					$data['error_message'] = $item['title'] . 'が不正です';
					$this->template->load('base_index', $head_error_path, $body_error_path, $data);
					return;
				}
				if($this->minista_library->is_contain_ng_word($item['explanation'])){
					$data['error_message'] = $item['explanation'] . 'が不正です';
					$this->template->load('base_index', $head_error_path, $body_error_path, $data);
					return;
				}
			}
			foreach($tags as $tag){
				if($this->minista_library->is_contain_ng_word($tag)){
					$data['error_message'] = $tag . 'が不正です';
					$this->template->load('base_index', $head_error_path, $body_error_path, $data);
					return;
				}
			}

			$minista = array(
				'user_id'      => $this->tank_auth->get_user_id(),
				't_target'     => $t_target,
				't_do'         => $t_do,
				't_how_many'   => $t_how_many,
				't_what'       => $t_what,
				'items'        => $items,
				'category_id'  => $category_id,
				'tags'         => $tags,
				'image_path'   => $image_path,
				'image_title'  => $image_title,
				'image_author' => $image_author
			);
			$this->Article_model->write_article($minista);
			$this->general_message('投稿', 'ミニスタを投稿しました');
			
		}
	}
	//状態：統合テスト待ち
	public function add_minista_item() {
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('title', 'タイトル', 'required|xss_clean|max_length[20]');
		$this->validation->set_rules('explanation', '説明文', 'xss_clean|max_length[100]');
		if($this->validation->run() == FALSE){
			// error
			$error_message = form_error('title') . form_error('explanation');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$article_id = $this->input->post('article_id');
			$title = $this->input->post('title');
			$explanation =  $this->input->post('explanation');
			
			// article_idが存在するかチェック
			if(!$this->Article_model->is_exists_article_id($article_id, FALSE)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// article_id::user_idとtank_auth::user_idを比較
			$user_id = $this->Article_model->get_user_id_by_article_id($article_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}

			// titleとexplanationの文法チェック
			if($this->minista_library->is_contain_ng_word($title)){
				$error_message = $title . 'が不正です';
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			if($this->minista_library->is_contain_ng_word($explanation)){
				$error_message = $explanation . 'が不正です';
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			
			
			$item = array(
				'user_id'     => $user_id,
				'article_id'  => $article_id,
				'title'       => $title,
				'explanation' => $explanation,
				'good'        => 0,
				'bad'         => 0,
				'stamp'       => date(DATE_ATOM)
			);
			$insert_id = $this->Article_model->add_item($item);
			$return_value = array(
				'state'       => MINISTA_IS_OK,
				'insert_id'   => $insert_id,
				'title'       => $title,
				'explanation' => $explanation
			);

			$this->minista_library->minista_ajax($return_value);
		}
	}

	// test [OK]
	public function vote() {
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}

		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}

		// validation
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('item_id', 'item_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('is_good', 'is_good', 'required|alpha_numeric|greater_than[-1]|less_than[2]');

		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$article_id = $this->input->post('article_id');
			$item_id = $this->input->post('item_id');
			$is_good = $this->input->post('is_good');
			$user_id = $this->tank_auth->get_user_id();

			// item_idとarticle_idが存在するかチェック
			if(!$this->Article_model->is_exists_article_id($article_id) || !$this->Article_model->is_exists_item_id($item_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// itemとuser_idから時間で投票の可否をチェック
			if($this->History_model->is_exist_vote_with_term($user_id, $item_id)){
				// 投票不可
				$error_message = $this->lang->line('ajax_error_minista_invalid_vote');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}else{
				// 投票可能
				$this->History_model->add_vote_history($user_id, $article_id, $item_id, $is_good, time());
				$this->Article_model->update_vote($item_id, $is_good);
				$return_value = array(
					'state' => MINISTA_IS_OK,
					'vote_state' => $is_good
				);
				$this->minista_library->minista_ajax($return_value);
			}
		}
	}
		
  // 単体テスト待ち
	private function _regist_favorite($article_id) {
		if ($this->tank_auth->is_logged_in()){
			$user_id = $this->tank_auth->get_user_id();
			$this->History_model->add_favorite_history($user_id, $article_id, time());
			$this->Article_model->update_favorite_count($article_id);
		} else {
			return NULL;
		}
	}
	
	public function search($page = 0, /*$category_id = 0,*/ $option = 0) { //統合テスト待ち
		$head_path = $this->path . 'search_head';
		$body_path = $this->path . 'search';
		
		$keyword = $this->input->get('keyword');
		switch($option) {
		case 1: //tag
			$data['articles'] = $this->Search_model->search_by_tag_name($keyword);
			$this->minista_library->add_username_to_all_articles($data['articles']);
			break;
		case 2: //tag (perfectly)
			$data['articles'] = $this->Search_model->search_by_tag_name($keyword, TRUE);
			$this->minista_library->add_username_to_all_articles($data['articles']);
			break;
		default:
			//$data['articles'] = $this->Search_model->search_by_keyword($keyword, $category_id);
			$user_id = 0;
			if ($this->tank_auth->is_logged_in()) {
				$user_id = $this->tank_auth->get_user_id();
			}
			$this->Search_model->log_keyword($user_id, $keyword);
			$data['articles'] = $this->Search_model->search_by_keyword($keyword, 0);
			$this->minista_library->add_username_to_all_articles($data['articles']);
		}
		
		$base_url = base_url() . "minista/search";
		$total_rows = count($data['articles']);
		$limit = Minista::PAGER_LIMIT;
		
		$data['pager'] = $this->pager->create_pager($base_url, $total_rows, $limit, $page, $data['articles'], "?keyword=".$keyword);
		
		$categories = $this->Category_model->get_all_categories();
		$data['categories'] = $categories;
		
		//$data['now_category'] = $category_id;
		$data['now_category'] = 0;
		//$data['keyword'] = $keyword;
		
		$this->template->load('base_index', $head_path, $body_path, $data);
	}

	/*
	|---------------------------------------------------------------------
	| Template sub page
	|---------------------------------------------------------------------
	*/
	public function about() {
		$head_path = $this->path . 'about_head';
		$body_path = $this->path . 'about';
		$data = array(
			'h1_title' => "Minista.jpとは",
		);
		$this->template->load('sub_page', $head_path, $body_path, $data);

	}
	public function term() {
		$head_path = $this->path . 'term_head';
		$body_path = $this->path . 'term';
		$data = array(
			'h1_title' => "利用規約",
		);
		$this->template->load('sub_page', $head_path, $body_path, $data);
	}
	public function privacy() {
		$head_path = $this->path . 'privacy_head';
		$body_path = $this->path . 'privacy';
		$data = array(
			'h1_title' => "プライバシー",
		);
		$this->template->load('sub_page', $head_path, $body_path, $data);
	}
	public function help() {
		$head_path = $this->path . 'help_head';
		$body_path = $this->path . 'help';
		$data = array(
			'h1_title' => "ヘルプ",
		);
		$this->template->load('sub_page', $head_path, $body_path, $data);
	}
	public function info() {
		$head_path = $this->path . 'info_head';
		$body_path = $this->path . 'info';
		$data = array(
			'h1_title' => "お問い合わせ",
		);
		$this->template->load('sub_page', $head_path, $body_path, $data);
	}
	public function general_message($h1_title = "", $message = "") {
		$head_path = $this->path . 'general_message_head';
		$body_path = $this->path . 'general_message';
		$data["h1_title"] = $h1_title;
		$data["message"] = $message;
		$this->template->load('sub_page', $head_path, $body_path, $data);
	}

	/*
	|---------------------------------------------------------------------
	| Template user page
	|---------------------------------------------------------------------
	*/

	public function my_page() {
		$head_path = $this->path . 'my_page_head';
		$body_path = $this->path . 'my_page';
		
		// セッションを調べる
		if(!$this->tank_auth->is_logged_in()){
			$this->general_message('エラー', 'ログインしてください');
			return;
		}

		// ユーザ基本情報取得
		$user_id = $this->tank_auth->get_user_id();
		$data['username'] = $this->tank_auth->get_username();
		$query = $this->user->get_email_by_id($user_id, TRUE);
		$data['email'] = $query->email;
			
		// ユーザ履歴取得
		$data['histories'] = $this->History_model->get_recent_history_limit($user_id, 10);
		if(!empty($data['histories'])){
		foreach($data['histories'] as $key => $history){
			$article= $this->Article_model->get_minista($history['article_id'], FALSE);
			$data['histories'][$key]['title'] = $article['article']['title'];
		}
		}
		$this->template->load('user_page', $head_path, $body_path, $data);
	}

	public function my_posted_article($offset = 0) {
		$head_path = $this->path . 'my_posted_article_head';
		$body_path = $this->path . 'my_posted_article';

		// セッションを調べる
		if(!$this->tank_auth->is_logged_in()){
			$this->general_message('エラー', 'ログインしてください．');
			return;
		}

		// validation
		if(!$this->validation->is_natural($offset) ||
			 !$this->validation->required($offset))
		{
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		// articleの取得
		$user_id = $this->tank_auth->get_user_id();
		$data['articles'] = $this->Article_model->get_articles_by_user_id($user_id);
		$this->minista_library->add_username_to_all_articles($data['articles']);

		// Pagerの取得
		$base_url = base_url() . "minista/my_posted_article";
		$total_rows = count($data['articles']);
		$limit = Minista::PAGER_LIMIT;
		$data['pager'] = $this->pager->create_pager($base_url,$total_rows,$limit,$offset,$data['articles']);
		$this->template->load('user_page', $head_path, $body_path, $data);
	}

	public function my_minista() {
		$head_path = $this->path . 'my_minista_head';
		$body_path = $this->path . 'my_minista';

		if(!$this->tank_auth->is_logged_in()){
			$this->general_message('エラー', 'ログインしてください．');
			return;
		}

		$user_id = $this->tank_auth->get_user_id();
		$data['folders'] = $this->My_minista_model->get_user_all_folders($user_id);
		$this->template->load('user_page', $head_path, $body_path, $data);
	}

	public function my_minista_folder($folder_id = 0, $offset = 0) {
		$head_path = $this->path . 'my_minista_folder_head';
		$body_path = $this->path . 'my_minista_folder';

		// loginチェック
		if(!$this->tank_auth->is_logged_in()){
			redirect('/auth/index');
			return;
		}

		// validation
		if(!$this->validation->required($folder_id) ||
			 !$this->validation->is_natural_no_zero($folder_id) ||
			 !$this->validation->required($offset) ||
			 !$this->validation->is_natural($offset))
		{
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		// folder_idが存在するかチェック
		if(!$this->My_minista_model->is_exists_folder_id_from_article_folder($folder_id)){
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}
		// user_idとfolder_idのuser_idが一致してるか
		$db_user_id = $this->My_minista_model->get_user_id_my_minista_folder($folder_id);
		$user_id = $this->tank_auth->get_user_id();
		if ($db_user_id !== $user_id){
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}
		
		$data['folder_id'] = $folder_id;
		$data['articles'] = $this->My_minista_model->get_user_all_articles($user_id, $folder_id);	
		$this->minista_library->add_username_to_all_articles($data['articles']);

		// Pagerセット
		$base_url = base_url() . "minista/my_minista_folder/" . $folder_id;
		$total_rows = count($data['articles']);
		$limit = Minista::PAGER_LIMIT;
		$data['pager'] = $this->pager->create_pager($base_url, $total_rows, $limit, $offset, $data['articles']);

		$this->template->load('user_page', $head_path, $body_path, $data);
	}
	// delete my minista_article
	public function delete_my_minista_article(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('folder_id', 'folder_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');

		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			$article_id = $this->input->post('article_id');
			$folder_id = $this->input->post('folder_id');

			// folder_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_folder_id_from_article_folder($folder_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// article_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_article_id_from_my_article($article_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}

			// user_idとfolder_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_folder($folder_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}
			// user_idとarticle_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_article($article_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}
			// myministaの削除
			$this->My_minista_model->delete_my_minista_article($article_id, $folder_id);

			$return_value = array(
				'state' => MINISTA_IS_OK,
				'article_id' => $article_id
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}

	// add my folder
	public function add_my_minista_folder(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}

		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}

		// validation
		$this->validation->set_rules('name', 'フォルダ名', 'required|xss_clean|max_length[' . Minista::MAX_TITLE_LENGTH . ']');
		if($this->validation->run() == FALSE){
			// error
			$error_message = form_error('name');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$name = $this->input->post('name');
			$user_id = $this->tank_auth->get_user_id();
			$folder_max_count = $this->user->get_folder_max_count_by_id($user_id, 1);
			$folder_current_count = $this->My_minista_model->get_folder_current_count($user_id);
			if($folder_max_count <= $folder_current_count){
				$error_message = sprintf($this->lang->line('ajax_error_over_limit_my_minista_folder'), $folder_max_count);
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}

			$insert_id = $this->My_minista_model->add_my_minista_folder($user_id, $name);
			$return_value = array(
				'state' => MINISTA_IS_OK,
				'insert_id' => $insert_id,
				'name' => $name
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}
	
	// delete my minista folder
	public function delete_my_minista_folder(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('folder_id', 'folder_id', 'required|is_natural_no_zero');

		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$folder_id = $this->input->post('folder_id');
			// folder_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_folder_id_from_article_folder($folder_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// user_idとfolder_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_folder($folder_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}
			//削除
			$this->My_minista_model->delete_my_minista_folder($folder_id);
			$return_value = array(
				'state' => MINISTA_IS_OK,
				'folder_id' => $folder_id
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}

	// delete my minista item
	public function delete_my_minista_item(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('item_id', 'article_id', 'required|is_natural_no_zero');
		
		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			$article_id = $this->input->post('article_id');
			$item_id = $this->input->post('item_id');

			// article_idとitem_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_item_id_from_my_item($article_id, $item_id) ||
				 !$this->My_minista_model->is_exists_article_id_from_my_article($article_id))
			{
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// user_idとfolder_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_item($item_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){
				return;}
			// 
			$this->My_minista_model->delete_my_minista_item($article_id, $item_id);
			$return_value = array(
				'state'   => MINISTA_IS_OK,
				'item_id' => $item_id
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}
		
	// add my minista
	public function add_my_minista(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('folder_id', 'folder_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');

		if($this->validation->run() == FALSE){
			// error
			$error_message = $this->lang->line('ajax_error_minista_invalid_request');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$folder_id = $this->input->post('folder_id');
			$article_id = $this->input->post('article_id');
		
			// folder_id, article_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_folder_id_from_article_folder($folder_id) ||
				 !$this->Article_model->is_exists_article_id($article_id))
			{
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// user_idとfolder_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_folder($folder_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){
				return;}
			// フォルダ内のミニスタの数を確認
			$my_minista_max_count = $this->My_minista_model->get_article_max_count($user_id, $folder_id);
			$my_minista_current_count = $this->My_minista_model->get_article_current_count($user_id, $folder_id);
			if($my_minista_max_count <= $my_minista_current_count){
				$error_message = sprintf($this->lang->line('ajax_error_over_limit_my_minista_article'), $my_minista_max_count);
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}				
			// 登録処理
			$data = $this->Article_model->get_minista($article_id);
			$insert_id = $this->My_minista_model->add_my_minista($user_id, $folder_id ,$data);
			$this->_regist_favorite($article_id);
			$return_value = array(
				'state' => MINISTA_IS_OK,
				'insert_id' => $insert_id
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}
	
	// add my minista item
	public function add_my_minista_item(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('article_id', 'article_id', 'required|is_natural_no_zero');
		$this->validation->set_rules('title', 'タイトル', 'required|xss_clean|max_length['.Minista::MAX_TITLE_LENGTH.']');
		$this->validation->set_rules('explanation', '説明文', 'xss_clean|max_length['.Minista::MAX_EXPLANATION_LENGTH.']');

		if($this->validation->run() == FALSE){
			// error
			$error_message = form_error('title') . form_error('explanation');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$article_id = $this->input->post('article_id');
			$title = $this->input->post('title');
			$explanation =  $this->input->post('explanation');
			
			// article_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_article_id_from_my_article($article_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			
			// article_id:user_idとtank_auth:user_idが同じかチェック
			$user_id = $this->My_minista_model->get_user_id_my_minista_article($article_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}

			// titleとexplanationの文法チェック
			if($this->minista_library->is_contain_ng_word($title)){
				$error_message = $title . 'が不正です';
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			if($this->minista_library->is_contain_ng_word($explanation)){
				$error_message = $explanation . 'が不正です';
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}

			// 登録の上限は今のところなし？
			// $my_minista_item_current_count = $this->My_minista_model->get_my_minista_item_current_count($user_id, $article_id);
			
			// 登録処理
			$insert_id = $this->My_minista_model->add_my_minista_item($user_id, $article_id, $title, $explanation);
			$return_value = array(
				'state'       => MINISTA_IS_OK,
				'insert_id'   => $insert_id,
				'title'       => $title,
				'explanation' => $explanation
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}

	//update my mnista folder
	public function update_my_minista_folder_name(){
		// ajaxのエラー
		if($this->minista_library->is_error_not_ajax_request_and_return_message()){return;}
		// 認証エラー
		if($this->minista_library->is_error_no_login_and_return_ajax_message()){return;}
		// validation
		$this->validation->set_rules('new_folder_name', 'フォルダ名', 'required|xss_clean|max_length[' . Minista::MAX_TITLE_LENGTH . ']');
		$this->validation->set_rules('folder_id', 'folder_id', 'required|is_natural_no_zero');
		if($this->validation->run() == FALSE){
			// error
			$error_message = form_error('new_folder_name');
			$this->minista_library->ajax_return_error_message($error_message);
			return;
		}else{
			// success
			$new_folder_name = $this->input->post('new_folder_name');
			$folder_id = $this->input->post('folder_id');
			// folder_idが存在するかチェック
			if(!$this->My_minista_model->is_exists_folder_id_from_article_folder($folder_id)){
				$error_message = $this->lang->line('ajax_error_minista_invalid_request');
				$this->minista_library->ajax_return_error_message($error_message);
				return;
			}
			// user_idとfolder_idのuser_idが一致してるか
			$user_id = $this->My_minista_model->get_user_id_my_minista_folder($folder_id);
			if($this->minista_library->is_error_invalid_user_and_return_ajax_message($user_id)){return;}
			$this->My_minista_model->update_my_minista_folder_name($folder_id, $new_folder_name);
			$return_value = array(
				'state' => MINISTA_IS_OK,
				'folder_id' => $folder_id,
				'new_folder_name' => $new_folder_name
			);
			$this->minista_library->minista_ajax($return_value);
		}
	}
	public function config() {
		$head_path = $this->path . 'config_head';
		$body_path = $this->path . 'config';
		
		if ($this->tank_auth->is_logged_in()){
			$this->template->load('user_page', $head_path, $body_path);
		} elseif ($this->tank_auth->is_logged_in(FALSE)) {
			redirect('/auth/send_again/');
		} else {
			redirect('/minista/');
		}
	}
	
	/*
	|---------------------------------------------------------------------
	| Template article page
	|---------------------------------------------------------------------
	 */
	
	public function my_minista_article($article_id = 0) {
		$head_path = $this->path . 'my_minista_article_head';
		$body_path = $this->path . 'my_minista_article';

		// loginチェック
		if(!$this->tank_auth->is_logged_in()){
			redirect('/auth/index');
			return;
		}

		// validation
		if(!$this->validation->is_natural_no_zero($article_id) ||
			 !$this->validation->required($article_id))
		{
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		// article_idが存在するかチェック
		if(!$this->My_minista_model->is_exists_article_id_from_my_article($article_id)){
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		// article_id::user_idとtank_auth::user_idを比較
		$db_user_id = $this->My_minista_model->get_user_id_my_minista_article($article_id);
		$user_id = $this->tank_auth->get_user_id();
		if ($db_user_id !== $user_id){
			$this->general_message("エラー","不正なリクエストです。");
			return;
		}

		// userのarticleとitemを取得
		$data['article'] = $this->My_minista_model->get_user_article($user_id, $article_id);
		$this->minista_library->add_username_to_article($data['article']);
		$data['items'] = $this->My_minista_model->get_user_all_items($user_id, $article_id);
		$this->template->load('article_page', $head_path, $body_path, $data);
	}

	/*
	public function test(){
		$data = array();
	}
	*/
}
?>
