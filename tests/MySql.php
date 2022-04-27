<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\dsn\dsn;
	use Traineratwot\PDOExtended\dsn\DsnException;
	use Traineratwot\PDOExtended\dsn\DsnHost;
	use Traineratwot\PDOExtended\PDOE;

	class MySql extends TestCase
	{
		/**
		 * @throws DsnException
		 */
		public function setUp()
		: void
		{
			parent::setUp();
			$dns = new DsnHost();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost('C:\light.db');
			$this->db = new PDOE($dns);
		}

		public function testConnect()
		{

			$tables = $this->db->getAllTables();
			$this->assertEquals('test', $tables[0], 'ok');
		}
	}
