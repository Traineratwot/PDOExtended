<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__. '/vendor/autoload.php';

	$sqLight = 'C:\light.db';
	$dns = new Dsn();
	$dns->setDriver(PDOE::DRIVER_SQLite);
	$dns->setHost($sqLight);
	$db = new PDOE($dns);
	var_dump($db->getScheme('type_test'));
	