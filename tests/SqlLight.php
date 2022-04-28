<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exception\DsnException;
	use Traineratwot\PDOExtended\PDOE;

	class SqlLight extends TestCase
	{
		/**
		 * @throws DsnException
		 */
		public function setUp()
		: void
		{
			parent::setUp();
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost('C:\light.db');
			$this->db = new PDOE($dns);
		}

		/**
		 * @throws DsnException
		 */
		public function testConnect()
		{
			$tables = $this->db->getAllTables();
			$this->assertEquals('test', $tables[0], 'Connect');
		}
	}
