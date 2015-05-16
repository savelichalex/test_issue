<?php
/**
 * Test cases for Client:
 * 		1. Read file and catch exception
 *		2. Check that static read return correct iterator
 * 		3. Test iterator
 */

require_once('../classes/CSV.php');
require_once('../classes/Output.php');

function init() {
	return $debug = OutputFabric::getInstance('debug');
}

function testCSVReader() {
	$debug = init();
	
	try {
		$iter = CSVReader::read('tett.csv');
	} catch(CSVOpenFileException $e) {
		$debug->write('Csv file does not exist correct');
		try {
			$iter = CSVReader::read('test.csv');
			$debug->assert($iter instanceof CSVIterator, 'CSVIterator is ');
		} catch(CSVOpenFileException $e) {
			$debug->write('Csv file does not exist wrong');
		}
	}
}

function testCSVIterator() {
	$debug = init();
	
	try {
		$iter = CSVReader::read('test.csv');
		$iter->first();
		$debug->assert($iter->getCurrent()[0] == 'Test Client', 'CSVIterator is ');
	} catch(CSVOpenFileException $e) {
		$debug->write('Csv file does not exist');
	}
}

//testCSVReader(); //correct
testCSVIterator();