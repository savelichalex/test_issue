<?php
class Config {
	private static $config = array();

	public static function set($name, $val) {
		self::$config[$name] = $val;
	}

	public static function get($name) {
		return self::$config[$name];
	}
}

?>