<?php

if(!class_exists('DB')) require_once('DB.php');
if(!class_exists('BaseException')) require_once('Exceptions.php');

/**
 * Implements of ActiveRecord pattern
 */
abstract class ActiveRecord {
	abstract public function save();
	abstract public static function findRandom();
}

/**
 * Instance of Client is table row
 * @throw ValidationException
 * @throw TableMissesException
 * @throw TableCreationFailedException
 */
class Client extends ActiveRecord {

	private static $table_name = "clients";

	private $name;

	private $status;

	private $insert = false;

	function __construct($n, $s = 0, $new_row = true) {
		if($this->_validateName($n)) {
			$this->name = $n;
		} else {
			throw new ValidationException("Uncorrect client name", 0);
		}
		if($s === 0 || $s === 1 || $s === '0' || $s === '1') {
			$this->status = +$s;
		} else {
			throw new ValidationException("Uncorrect status", 0);
		}
		$this->insert = !$new_row;
	}

	/**
	 * Save client in db
	 * @throw TableMissesException
	 */
	public function save() {
		try {
			if(!$this->insert) {
				DB::getInstance()->insert(
										self::$table_name,
										array(
											'status' => $this->status,
											'name' => $this->name
											)
										 );
				$this->insert = true;
			} else {
				DB::getInstance()->update(
										self::$table_name,
										array('status' => $this->status),
										array('name' => $this->name)
									 	 );
			}
		} catch(PDOException $e) {
			if($e->getCode() === '42S02') {
				throw new TableMissesException($e->getMessage(), 0, $e);
			} else {
				throw $e;
			}
		}
	}

	/**
	 * Search random row in table
	 * and return new client
	 * @return Client;
	 * @throw TableMissesException
	 */
	public static function findRandom() {
		try {
			$row = DB::getInstance()->selectRandomRow(self::$table_name);
			return new Client($row['name'], $row['status'], false);
		} catch(PDOException $e) {
			if($e->getCode() === '42S02') {
				throw new TableMissesException($e->getMessage(), 0, $e);
			} else {
				throw $e;
			}
		}
		
	}

	/**
	 * Create new clients table
	 * @throw TableCreationFailedException
	 */
	public static function newTable() {
		try {
			DB::getInstance()->createTable(self::$table_name, array('name' => 'VARCHAR(80)', 'status' => 'INT(1)'));
		} catch(TableCreationFailedException $e) {
			throw $e;
		}		
		
	}

	public function getName() {
		return $this->name;
	}

	public function getStatus() {
		return $this->status;
	}

	public function changeStatus() {
		if($this->status == 0) {
			$this->status = 1;
		} else {
			$this->status = 0;
		}
	}

	private function _validateName($name) {
		return true; //stab
	}
}

?>