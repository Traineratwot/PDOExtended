<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\PDOE;

	require_once dirname(__DIR__, 1) . '/vendor/autoload.php';

	$dns = new Dsn();
	$dns->setDriver(PDOE::DRIVER_MySQL);
	try {
		$dns->setHost('localhost');
	} catch (DsnException $e) {
	}
	$dns->setUsername('root');
	$dns->setPassword('root');
	$dns->setDatabase('ftp-technolight');

		$db = new PDOE($dns);


		$create = $db->newTable('test')
					 ->addString('id')
					 ->setPrimaryKey('id')
					 ->addInt('data')
		;

		var_dump($create->toSql());
