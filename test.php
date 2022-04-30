<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__. '/vendor/autoload.php';

	$dns = new Dsn();

//	$sqLight = 'C:\light.db';
//	$dns = new Dsn();
//	$dns->setDriver(PDOE::DRIVER_SQLite);
//	$dns->setHost($sqLight);
//	$db = new PDOE($dns);
//	var_dump($db->getScheme('test_link_master'));
//
//	die();
	$dns->setDriver(PDOE::DRIVER_MySQL);
	$dns->setHost('localhost');
	$dns->setUsername('root');
	$dns->setPassword('');
	$dns->setDatabase('test');
	$db = new PDOE($dns);
	$pool = $db->poolPrepare('INSERT INTO test (`value`)VALUES(:value)');
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->execute(['value' => random_int(0, 1000)]);
	$pool->run();
	$c=  $db->query("SELECT count(*) from test")->fetch(PDO::FETCH_COLUMN);
	$this->assertSame($c, 8);
	