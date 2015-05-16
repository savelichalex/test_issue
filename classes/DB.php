<?php

if(!class_exists('BaseException')) require_once('Exceptions.php');

/**
 * Class to work with DB
 * This is singleton
 */
class DB {

	private static $host;
	private static $dbname;
	private static $charset;
	private static $user;
	private static $pass;

	//PDO instance
	private $pdo;

	private static $instance = null;

	private function __construct() {
		$dsn = "mysql:host=".self::$host.
			   ";dbname=".self::$dbname.
			   ";charset=".self::$charset;

		$opt = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		);

		try {
			$this->pdo = new PDO($dsn, self::$user, self::$pass, $opt);
		} catch (PDOException $e) {
			throw new ConnectionMissesException("DB connection failed", 0, $e);			
		}
	}

	private function __clone() {

	}

	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new DB();
		}
		return self::$instance;
	}

	public static function setOptions($opt) {
		self::$host = $opt['host'];
		self::$dbname = $opt['dbname'];
		self::$charset = $opt['charset'];
		self::$user = $opt['user'];
		self::$pass = $opt['pass'];
		if(self::$instance !== NULL) 
			return self::$instance = new DB();
	}

	/**
	 * Insert new row in table
	 * @param string $table_name
	 * @param array $fields 
	 */
	public function insert($table_name, $fields) {
		$sql = "INSERT INTO $table_name SET ".$this->_createFieldsStr($fields);
		$stm = $this->pdo->prepare($sql);
		return $stm->execute($fields);
	}

	/**
	 * Update row in table
	 * @param string $table_name
	 * @param array $fields 
	 * @param array $where
	 */
	public function update($table_name, $fields, $where) {
		$sql = "UPDATE $table_name SET ".$this->_createFieldsStr($fields).
			   " WHERE ".$this->_createWhereStr($where, $fields);
		$stm = $this->pdo->prepare($sql);
		return $stm->execute($fields);
	}

	/**
	 * Return all fields in row
	 * @param string $table_name
	 * @param array $where
	 * @return int $limit
	 */
	public function selectAllInRow($table_name, $where, $limit = NULL) {
		$sql = "SELECT * FROM $table_name WHERE ".$this->_createWhereStr($where);
		if($limit != NULL) $sql .= " LIMIT $limit";
		$stm = $this->pdo->prepare($sql);
		$stm->execute($where);
		return $stm->fetch();
	}

	/**
	 * Return random row in table
	 * @param string $table_name
	 * @param array $fields 
	 * @param array $where
	 * @return array $row
	 */
	public function selectRandomRow($table_name) {
		$sql = "SELECT COUNT(*) FROM $table_name";
		$stm = $this->pdo->prepare($sql);
		$stm->execute();
		$row_count = (int) $stm->fetch()['COUNT(*)'];

		$rand_row = rand(0,$row_count-1);

		$sql = "SELECT * FROM $table_name LIMIT $rand_row, 1";
		$stm = $this->pdo->prepare($sql);
		$stm->execute();
		return $stm->fetch();
	}

	/**
	 * Create new table
	 * @param string $table_name
	 * @param array $cols is titles and types
	 * @throw TableCreationFailedException
	 */
	public function createTable($table_name, $cols) {
		try{
			$sql = "CREATE TABLE $table_name (".
					$this->_createTableStr($cols).")";
			$this->pdo->exec($sql);
		} catch(PDOException $e) {
			throw new TableCreationFailedException($e->getMessage(), 0, $e);
		}
	}

	private function _createFieldsStr($fields) {
		$str = '';
		foreach($fields as $key => $val)
			$str .= "`".str_replace("`","``",$key)."`"."=:$key, ";
		return substr($str, 0, -2);
	}

	private function _createWhereStr($where, &$fields = NULL) {
		$str = '';
		foreach($where as $key => $val) {
			$str .= "`".str_replace("`","``",$key)."`"."=:$key AND ";
			if($fields != NULL) $fields[$key] = $val;
		}
		return substr($str, 0, -5);
	}

	private function _createTableStr($cols) {
		$str = '';
		foreach($cols as $key => $val)
			$str .= " $key $val, ";
		return substr($str, 0, -2);
	}
}