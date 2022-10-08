<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__ . '/vendor/autoload.php';


	$dns = new Dsn();

	$sqLight = 'C:\light.db';


	$dns = new Dsn();
	$dns->setDriver(PDOE::DRIVER_SQLite);
	$dns->setHost($sqLight);
	$db = new PDOE($dns);

	$sql = $db->newTable('test878')
			  ->dropTable()
			  ->setComment('таблица test878')
			  ->addInt('id')
			  ->addInt('count', 15, TRUE, comment: 'tfdds')
			  ->addString('lgkdfl', 0, TRUE, NULL, "комментарий")
			  ->setPrimaryKey('id')
			  ->toSql()
	;
	echo($sql);
	die;