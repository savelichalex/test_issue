<?php

mb_internal_encoding("UTF-8");

require_once('classes/DB.php');
require_once('classes/ActiveRecord.php');
require_once('classes/CSV.php');
require_once('classes/Output.php');
require_once('classes/Config.php');
require_once('config.php');

function main() {
	DB::setOptions(Config::get('db_options'));
	
	function showClient() {
		$output = OutputFabric::getInstance(Config::get('output_type'));
		try {
			$rand_client = Client::findRandom();
			$rand_client->changeStatus();
			$rand_client->save();
			$output->write($rand_client->getName().';'.$rand_client->getStatus());
		} catch(TableMissesException $e) {
			try {
				Client::newTable();
			} catch(TableCreationFailedException $e) {
				$output->write($e->getMessage());
				die();
			}
			try {
				$iter = CSVReader::read(Config::get('csv_file'));
			} catch(CSVOpenFileException $e) {
				$output->write($e->getMessage());
				die();
			}
			for($iter->first(); !$iter->isDone(); $iter->next()) {
				try {
					$client = new Client($iter->getCurrent()[0], $iter->getCurrent()[1]);
				} catch(ValidationException $e) {
					continue;
				}				
				$client->save();
			}
			showClient();
		} catch(ConnectionMissesException $e) {
			$output->write($e->getMessage());
			die();
		}
	}

	showClient();
}

main();
?>