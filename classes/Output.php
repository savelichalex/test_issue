<?php
abstract class Output {
	abstract function write($str);
}

class ConsoleOutput extends Output {
	public function write($str) {
		echo iconv("utf-8","cp866",$str);
	}
}

class HtmlOutput extends Output {
	public function write($str) {
		echo "<!DOCTYPE html><html><head><title>Test</title></head><body>".$str."</body></html>";
	}
}

/*
class LogOutput extends Output {
	public function write($str) {
		//echo $str; put str into file + Date
	}
}
*/

class DebugOutput extends Output {
	public function write($str) {
		echo $str."\n";
	}

	public function assert($condition, $str) {
		if($condition) {
			$str .= " correct\n";
		} else {
			$str .= " wrong\n";
		}
		echo $str;
	}
}

class OutputFabric {
	public static function getInstance($type) {
		switch($type) {
			case 'console':
				return new ConsoleOutput();
				break;
			case 'html':
				return new HtmlOutput();
				break;
			case 'log':
				return new LogOutput();
				break;
			case 'debug':
				return new DebugOutput();
				break;
		}
	}
}