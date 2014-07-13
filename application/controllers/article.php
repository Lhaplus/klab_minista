<?php

class Article extends CI_Controller {
        
        public function __construct() {
                parent::__construct();
                $this->load->helper('form');
                $this->load->helper('url');
                $this->load->model('Article_model');
        }
        
        public function top() {
                $data = array(
                        'articles' => $this->Article_model->getAllArticles(),
                );
                $this->load->view('article_top_view', $data);
        }
        
        public function view($id) {
                $this->Article_model->view($id, $article, $lists);
                if(!isset($article)) {
                        redirect('article/top');
                }
                $data = array(
                        'article' => $article,
                        'lists' => $lists,
                );
                $this->load->view('article_view', $data);
        }
        
        public function create() {
                $this->load->view('article_create_view');
        }
        
        public function create_form() {
                $data = array(
                        'title' => $this->input->post('title'),
                );
                $insert_id = $this->Article_model->create($data);
				$data['insert_id'] = $insert_id;
                $this->load->view('article_create_end_view', $data);
        }
        
        public function addList($id) {
                $data = array(
                        'id' => $id,
                );
                $this->load->view('article_addList_view', $data);
        }
        
        public function addList_form() {
                $data = array(
                        'article_id' => $this->input->post('id'),
                        'title' => $this->input->post('title'),
                );
                $this->Article_model->addList($data);
                $this->load->view('article_addList_end_view', $data);
        }
        
        public function vote() {
                
        }
        
}

?>