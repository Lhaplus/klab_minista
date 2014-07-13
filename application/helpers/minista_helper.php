<?php

if(!function_exists('is_strings_blank')) {
	function is_strings_blank() {
		$strings = func_get_args();
		$num = count($strings);
		for($i=0;$i<$num;$i++) {
			if(is_array($strings[$i])) {
				$num += count($strings[$i]);
				foreach($strings[$i] as $string) {
					array_push($strings, $string);
				}
			} else {
				if(is_string($strings[$i])) {
					if(strlen($strings[$i]) != 0) {
						return FALSE;
					}
				} else {
					return FALSE;
				}
			}
		}
		return TRUE;
	}
}

if(!function_exists('debug_view')) {
	function debug_view ($what) {
		echo '<pre>';
		if ( is_array($what) )  {
			print_r ($what);
		} else {
			var_dump ($what);
		}
		echo '</pre>';
	}
}

?>