<?php
	/** @noinspection GlobalVariableUsageInspection */


	use PHPUnit\Framework\TestCase;
	use Traineratwot\config\Config;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\PDOE;

	class TestCreate extends TestCase
	{

		/**
		 * @throws DsnException
		 */
		/**
		 * @throws DsnException
		 * @throws PDOEException
		 */
		public function setUp()
		: void
		{
			parent::setUp();
			Config::set('CACHE_PATH', __DIR__ . '/cache/');
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_MySQL);
			$dns->setHost('localhost');
			$dns->setUsername('root');
			$dns->setPassword('');
			$dns->setDatabase('test');
			$this->db = new PDOE($dns);
			$this->db->logOn();
			$this->db->execFile(__DIR__ . DIRECTORY_SEPARATOR . 'mysql.sql');
		}

		public function testCreate()
		{
			$this->assertTrue(TRUE);
		}
	}
