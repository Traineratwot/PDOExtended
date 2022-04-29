<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exception\DsnException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	class TestHelpers extends TestCase
	{

		public function testPrepare()
		{
			$sql1 = "SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < :calories AND colour = :colour;;;;";
			$sql2 = '  SELECT "name", colour, calories FROM fruit WHERE calories < ? AND colour = ?;   ';
			$sql3 = "SELECT * FROM issues WHERE tag::jsonb ?? ?";

			$sql1_ = Helpers::prepare($sql1, ['calories' => 150, 'colour' => 'red']);
			$sql2_ = Helpers::prepare($sql2, [150, 'red']);
			$sql3_ = Helpers::prepare($sql3, [150, 'red']);

			$this->assertEquals("SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < 150 AND colour = red;", $sql1_, 'test');
			$this->assertEquals('SELECT "name", colour, calories FROM fruit WHERE calories < 150 AND colour = red;', $sql2_, 'test');
			$this->assertEquals("SELECT * FROM issues WHERE tag::jsonb ?? 150;", $sql3_, 'test');
		}

		/**
		 * @throws DsnException
		 */
		public function testDsn()
		{
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost('C:\light.db');
			$this->assertEquals("sqlite:C:\light.db", $dns->get(), 'sqlite');

			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_MySQL);
			$dns->setHost('localhost');
			$dns->setUsername('root');
			$dns->setPassword('');
			$dns->setDatabase('test');
			$this->assertEquals("mysql:host=localhost:3306;dbname=test;charset=utf8;", $dns->get(), 'sqlite');

			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_PostgreSQL);
			$dns->setHost('127.0.0.1');
			$dns->setUsername('postgres');
			$dns->setPassword('');
			$dns->setDatabase('test');
			$this->assertEquals("pgsql:host=127.0.0.1;port=5432;dbname=test;", $dns->get(), 'sqlite');
		}
	}
