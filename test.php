<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__ . '/vendor/autoload.php';


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
//	$sql = $db->table('test')->select()
//							 ->where()
//							 ->in('id',[5,6,8])
//							 ->and()
//							 ->notEq('id',5)
//							 ->toSql();
	$sql = $db->table('test')->select()
			  ->addColumn('id')
			  ->addColumn('value')
			  ->limit(1,2)
			  ->orderBy([
				  'id'=>"asc"
						])
			  ->where(function ($w) {
				  $w->in('id', [5, 6, 8])
					->or()
					->less('id', 5)
				  ;
			  })->end()
			  ->toSql()
	;

	echo '<pre>';
	var_dump($sql);
	die;