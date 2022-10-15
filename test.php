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
			  ->addEnum('test', ['t1','t2','t3',0])
			  ->setPrimaryKey('id')
			  ->addUniqueKey(['count','lgkdfl'])
			  ->toSql()
	;
	echo($sql);
	die;