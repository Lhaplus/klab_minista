<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
  var $ci;
  
  function __construct() {
    $this->ci =& get_instance();
  }

	function load($tpl_view, $head_view = null, $body_view = null, $data = null) {
		if ( !( (is_null( $head_view )) && (is_null( $body_view )))) {
      if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$head_view ) ) {
        $head_view_path = $tpl_view.'/'.$head_view;
      } else if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$head_view.'.php' ) ) {
        $head_view_path = $tpl_view.'/'.$head_view.'.php';
      } else if ( file_exists( APPPATH.'views/'.$head_view ) ) {
        $head_view_path = $head_view;
      } else if ( file_exists( APPPATH.'views/'.$head_view.'.php' ) ) {
        $head_view_path = $head_view.'.php';
      } else {
        show_error('Unable to load the requested file: ' . $tpl_view.'/'.$head_view.'.php');
      }


			if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view ) ) {
				$body_view_path = $tpl_view.'/'.$body_view;
			} else if ( file_exists( APPPATH.'views/'.$tpl_view.'/'.$body_view.'.php' ) ) {
				$body_view_path = $tpl_view.'/'.$body_view.'.php';
			} else if ( file_exists( APPPATH.'views/'.$body_view ) ) {
				$body_view_path = $body_view;
			} else if ( file_exists( APPPATH.'views/'.$body_view.'.php' ) ) {
				$body_view_path = $body_view.'.php';
			} else {
				show_error('Unable to load the requested file: ' . $tpl_view.'/'.$body_view.'.php');
			}

			$head = $this->ci->load->view($head_view_path, TRUE);
			$body = $this->ci->load->view($body_view_path, $data, TRUE);

			if ( is_null($data) ) {
				$data = array('head' => $head, 'body' => $body);
			} else if ( is_array($data) ) {
				$data['head'] = $head;
				$data['body'] = $body;
			} else if ( is_object($data) ) {
				$data->head = $head;
				$data->body = $body;
			}
		}

		$this->ci->load->view('templates/'.$tpl_view, $data);
	}
}
?>