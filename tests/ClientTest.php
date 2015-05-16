<?php
/**
 * Test cases for Client:
 * 		1. Client creation
 *		2. Clien creation validation (name)
 * 		3. Client creation validation (status)
 *		4. Test table creation
 *		5. Test save method
 *		6. Test find random method
 */

require_once('../classes/DB.php');
require_once('../classes/ActiveRecord.php');
require_once('../classes/Output.php');

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

function testTableCreation() {
	$debug = init();
	
	try {
		CLient::newTable();
		$debug->write('Table creation is correct');
	} catch(TableCreationFailedException $e) {
		$debug->write('Table creation is wrong');
		$debug->write($e->getMessage());
	}
}

function testClientCreation() {
	$debug = init();
	$client = new Client('Test Client', 0);
	$debug->assert($client instanceof Client, 'Client creation is ');
}

function testClientCreationValidationName() {
	$debug = init();
	//validation name is stub
}

function testClientCreationValidationStatus() {
	$debug = init();
	try {
		$client = new Client('Test Client', '#$');		
		$debug->write('Validation status is wrong');
	} catch(ValidationException $e) {		
		$debug->write('Validation status is correct');
	}
}

function testSave() {
	$debug = init();
	
	$client = new Client('Test Client', 0);
	$client->save();
}

function testFindRandom() {
	$debug = init();
	
	$client = Client::findRandom();
	$debug->assert($client->getName() == 'Test Client', 'Find random is ');
}

testClientCreation(); //correct
testClientCreationValidationStatus(); //correct
testTableCreation(); //correct
testSave(); //correct
testFindRandom(); //correct

?>