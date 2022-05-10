<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__ . '/vendor/autoload.php';


	$dns = new Dsn();

	$sqLight = 'C:\light.db';
	$dns     = new Dsn();
	$dns->setDriver(PDOE::DRIVER_SQLite);
	$dns->setHost($sqLight);
	$db = new PDOE($dns);

	$sql = $db->table('test_link_master')->alter()
			  ->addCol('test_890', 'varchar', 20)
			  ->addCol('test_890', 'varchar', 20)
//			  ->join('test_link_slave')->left()
			  ->toSql()
	;
	echo '<pre>';
	var_dump($sql);
	die;