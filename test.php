<?php

	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\PDOE;

	require __DIR__ . '/vendor/autoload.php';


	$dns = new Dsn();

	$sqLight = 'C:\light.db';
	$dns     = new Dsn();
	$dns->setDriver(PDOE::DRIVER_SQLite);
	$dns->setHost($sqLight);
//	$db = new PDOE($dns);
//	var_dump($db->getScheme('test_link_master'));
//
//	die();
//	$dns->setDriver(PDOE::DRIVER_MySQL);
//	$dns->setHost('localhost');
//	$dns->setUsername('root');
//	$dns->setPassword('');
//	$dns->setDatabase('test');
	$db = new PDOE($dns);
//	$sql = $db->table('test')->select()
//							 ->where()
//							 ->in('id',[5,6,8])
//							 ->and()
//							 ->notEq('id',5)
//							 ->toSql();
//	$sql = $db->table('test_link_master')->select()
//		->where(function (Where $w) {
//			$w->and(function (Where $w) {
//				$w->eq('id', 5);
//			});
//			$w->or(function (Where $w) {
//				$w->notEq('id', 8);
//				$w->and();
//				$w->notEq('id', 9);
//			});
//
//		})->end()
//		->toSql();
//	;


	$sql = $db->table('test_link_master')->update()
			  ->set('master','t')
//			  ->join('test_link_slave')->left()
			  ->toSql()
	;;
	echo '<pre>';
	var_dump($sql);
	die;