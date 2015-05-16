<?php

if(!class_exists('BaseException')) require_once('Exceptions.php');

/**
 * This class has one static method
 * to read and parse csv file
 * return iteraror
 * @throw CSVOpenFileException
 */
class CSVReader {

	/**
	 * Read and parse csv file
	 * @param string $csv_file
	 * @return CSVIterator
	 */
	public static function read($csv_file) {
		$result = array();
		if(($handle = fopen($csv_file, "r")) !== false) {
			while(($data = fgetcsv($handle, 1000, ";")) !== false) {
				array_push($result, $data);
			}
			fclose($handle);
		} else {
			throw new CSVOpenFileException("CSV file does not exist", 0);
		}
		return new CSVIterator($result);
	}

	private function __construct() {}

	private function __clone() {}
}

class CSVIterator {
	private $data;

	private $i = 0;

	private $length;

	public function __construct($arr) {
		$this->data = $arr;
		$this->length = count($this->data);
	}

	public function first() {
		$this->i = 0;
	}

	public function isDone() {
		return $this->i == ($this->length - 1) ? true : false;
	}

	public function next() {
		$this->i++;
	}

	public function getCurrent() {
		return $this->data[$this->i];
	}
}
?>