<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\PDOE;

	class PostgreSql extends TestCase
	{
		/**
		 * @throws DsnException
		 */
		public function setUp()
		: void
		{
			parent::setUp();
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_PostgreSQL);
			$dns->setHost('127.0.0.1');
			$dns->setUsername('postgres');
			$dns->setPassword('');
			$dns->setDatabase('test');
			$this->db = new PDOE($dns);
		}

		/**
		 * @throws DsnException
		 */
		public function testConnect()
		{
			$tables = $this->db->getTablesList();
			$this->assertEquals('test', $tables[0], 'Connect');
		}
	}
