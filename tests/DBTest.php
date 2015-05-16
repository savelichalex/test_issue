<?php
/**
 * Test cases for DB:
 * 		1. Catch connection misses exception
 *		2. Catch table creation failed exception
 *		4. Test insert method
 *		5. Test update method
 *		6. Test select random method
 */

require('../classes/DB.php');
require('../classes/Output.php');

function init() {
	DB::setOptions(array(
							'host' => 'localhost',
							'dbname' => 'test_db',
							'charset' => 'utf8',
							'user' => 'root',
							'pass' => ''
						));
	return $debug = OutputFabric::getInstance('debug');
}


function testConnectionMissesException() {
	DB::setOptions(array(
							'host' => 'localhost',
							'dbname' => 'test_d',
							'charset' => 'utf8',
							'user' => 'root',
							'pass' => ''
						));
	return $debug = OutputFabric::getInstance('debug');
	try {
		DB::getInstance();
		$debug->write('Catch connection misses exception is correct');
	} catch(ConnectionMissesException $e) {
		$debug->write("Catch connection misses exception is wrong\n");
		$debug->write($e->getMessage());
	}
}

function testTableCreationFailedException() {
	$debug = init();
	try {
		DB::getInstance()->createTable('test_table', array(
															'id' => 'INT NOT NULL AUTO_INCREMENT',
															'name' => 'VARCHAR(80)',
															'PRIMARY KEY' => '(id)'
														 ));
		$debug->write('Catch connection misses exception is correct');
	} catch(ConnectionMissesException $e) {
		$debug->write('Catch connection misses exception is wrong');
		$debug->write($e->getMessage());
	}
}
function testInsertMethod() {
	$debug = init();
	DB::getInstance()->insert('test_table', array('name' => 'Test Test'));
	$row = DB::getInstance()->selectAllInRow('test_table', array('name' => 'Test Test'));
	$debug->assert($row['name'] == 'Test Test', 'Test insert method is ');
}

function testUpdateMethod() {
	$debug = init();
	DB::getInstance()->update('test_table', array('name' => 'Test Work'), array('id' => 1));
	$row = DB::getInstance()->selectAllInRow('test_table', array('id' => 1));
	$debug->assert($row['name'] == 'Test Work', 'Test update method is ');
}

function testSelectRandomMethod() {
	$debug = init();
	$row = DB::getInstance()->selectRandomRow('test_table');
}

testConnectionMissesException(); //correct
testTableCreationFailedException(); //correct
testInsertMethod(); //correct
testUpdateMethod(); //correct
testSelectRandomMethod(); //correct
?>