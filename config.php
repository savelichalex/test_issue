<?php
if(!class_exists('Config')) require_once('classes/Config.php');

Config::set('db_options',array(
								'host' => 'localhost',
								'dbname' => 'test_db',
								'charset' => 'utf8',
								'user' => 'root',
								'pass' => ''
							  )
			);
Config::set('output_type', 'console');
Config::set('csv_file', 'test.csv');
?>