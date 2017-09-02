<?php
namespace Module\Util;

class Timer {
	var $start;
	var $pause_time;

	function timer($start = 0) {
		if($start) { $this->start(); }
	}

	function start() {
		$this->start = $this->get_time();
		$this->pause_time = 0;
	}

	function pause() {
		$this->pause_time = $this->get_time();
	}

	function unpause() {
		$this->start += ($this->get_time() - $this->pause_time);
		$this->pause_time = 0;
	}

	function get($decimals = 8) {
		return round(($this->get_time() - $this->start),$decimals);
	}

	function get_time() {
		list($usec,$sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);
	}
}