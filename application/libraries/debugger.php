<?php

class Debugger {
	private $debug_mode = FALSE;
	public function __construct() {
		
	}
	
	public function var_dump($variable) {
		if($this->debug_mode) {
			echo '<pre>';
			if ( is_array($variable) )  {
				print_r ($variable);
			} else {
				var_dump ($variable);
			}
			echo '</pre>';
		}
	}
	
	public function set_debug_mode($flag) {
		if(is_bool($flag)) {
			$this->debug_mode = $flag;
		}
	}
}

?>