<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__. '/vendor/autoload.php';


	$dns = new Dsn();
	$dns->setDriver(PDOE::DRIVER_PostgreSQL);
	$dns->setHost('localhost');
	$dns->setUsername('root');
	$dns->setPassword('');
	$dns->setDatabase('test');
	echo '<pre>';
	var_dump($dns->get()); die;
