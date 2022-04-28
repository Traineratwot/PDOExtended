<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exception\DsnException;
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
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_MySQL);
			$dns->setHost('localhost');
			$dns->setUsername('root');
			$dns->setPassword('');
			$dns->setDatabase('test');
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
