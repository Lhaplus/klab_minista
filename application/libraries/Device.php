<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Device {
	var $ci;
	var $sp_path;
	var $sp_flg;
	function __construct() {
		$this->sp_path = '';
		$this->sp_flg = FALSE;
		$this->ci =& get_instance();

		if($this->sp_flg === TRUE){
			$this->sp_path = 'sp/';
		}
	}

	function get_sp_path() {
		return $this->sp_path;
	}

	function get_sp_flg() {
		return $this->sp_flg;
	}
}
