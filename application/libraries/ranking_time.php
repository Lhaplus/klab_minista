<?php

class Ranking_time {
	private $default_create_hour = 6;
	public function __construct() {
		
	}
	
	public function get_default_create_hour() {
		return $this->default_create_hour;
	}
	
	public function get_hourly_ranking_time($hours_ago = 0) {
		
	}
	
	public function get_daily_ranking_time($days_ago = 0) {
		$h = date("H");
		if($h < $this->default_create_hour) {
			$off = -1*24*60*60;
		} else {
			$off = 0;
		}
		$ret = $this->get_today($off) + ($this->default_create_hour*60*60) - ($days_ago*24*60*60);
		return $ret;
	}
	
	public function get_weekly_ranking_time($weeks_ago = 0) {
		$h = date("H");
		$w = date("N");
		if($w == 1 && $h < $this->default_create_hour) {
			$off = -1*7*24*60*60;
		} else {
			$off = -1*($w-1)*24*60*60;
		}
		$ret = $this->get_today($off) + ($this->default_create_hour*60*60) - ($weeks_ago*7*24*60*60);
		return $ret;
	}
	
	public function get_monthly_ranking_time($months_ago = 0) {
		$h = date("H");
		$d = date("j");
		if($d == 1 && $h < $this->default_create_hour) {
			$ret = $this->get_last_month();
		} else {
			$ret = $this->get_last_month(date("n")+1);
		}
		for($i=0;$i<$months_ago;$i++) {
			$m = date('n', $ret);
			$ret = $this->get_last_month($m);
		}
		return $ret;
	}
	
	public function get_today($offset = 0) {
		$y = date("Y");
		$m = date("n");
		$d = date("j");
		
		return mktime(0,0,0,$m,$d,$y) + $offset;
	}
	
	public function get_last_month($m = NULL) {
		$y = date("Y");
		if(!isset($m)) {
			$m = date("n");
		}
		return mktime(0,0,0,$m-1,1,$y);
	}
	
	public function get_last_month_length() {
		$y = date("Y");
		$m = date("n");
		$t = mktime(0,0,0,$m-1,1,$y);
		
		return get_month_length($t);
	}
	
	public function get_month_length($time) {
		return date("t", $time);
	}
	
	public function get_create_time_of_today() {
		return $this->get_today() + ($this->get_default_create_hour()*60*60);
	}
	
	public function get_create_time_of_hour() {
		$now = time();
		$dif = $now % 3600;
		return ($now - $dif);
	}
}

?>