<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pager {
	var $ci;
	
	function __construct() {
		$this->ci =& get_instance();
		$this->ci->load->library('pagination');
		$config['full_tag_open'] = "<div class='top-pager'><ul class='pure-paginator'>";
		$config['full_tag_close'] = '</ul></div>';
		$config['anchor_class'] = 'pure-button prev';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = "<li><a class='pure-button pure-button-active' href=''>";
		$config['cur_tag_close'] = '</a></li>';
		$config['first_link'] = FALSE;
		$config['last_link'] = FALSE;
		$this->ci->pagination->initialize($config);
	}
	
	/*
	 | $base_url: base url
	 | $tota_rows: total rows
	 | $limit: number of rows which you want to show
	 | $offset: starting point
	 | $from: data
	 | memo: This method is cut off $from from $offset to $offset + $limit
	 |       and create pager
	 */
	function create_pager($base_url, $total_rows, $limit, $offset, &$from, $suffix = '') {

		$config['base_url'] = $this->relative_to_absolute($base_url);
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $limit;
		$config['suffix'] = $suffix;
		$config['first_url'] = $base_url.$suffix;
		$config['uri_segment'] = substr_count($base_url, '/') - 1;
		$this->ci->pagination->initialize($config);
		
    $offset = is_numeric($offset) ? (int) $offset : 0 ;
		$offset = ((int) $total_rows <  $offset ) ? (int) $total_rows - $limit : $offset; 

		$cut_point = $offset+$limit;
		if($cut_point >= $total_rows){$cut_point = $total_rows;}
		if($from != null){
			array_splice($from, $cut_point, $total_rows);
			array_splice($from, 0, $offset);
			return $this->ci->pagination->create_links();
		}else{
			return null;
		}
	}


	function get_sp_flg() {
		return $this->sp_flg;
	}
	
	private function relative_to_absolute($url) {
		if(strpos($url, "http") != 0) {
			$this->ci->load->helper('url');
			return base_url().$url;
		} else {
			return $url;
		}
	}
}
