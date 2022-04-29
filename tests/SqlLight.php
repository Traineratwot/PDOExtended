<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\PDOE;

	class SqlLight extends TestCase
	{
		/**
		 * @throws DsnException|PDOEException
		 */
		public function setUp()
		: void
		{
			parent::setUp();
			$sqLight = __DIR__ . '/test.db';
			$f       = fopen($sqLight, 'wb');
			fclose($f);
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost($sqLight);
			$this->db = new PDOE($dns);
			$this->db->exec("CREATE TABLE test
(
    id    INTEGER NOT NULL,
    value INTEGER
);
");
		}

		public function tearDown()
		: void
		{
			unset($this->db);
			if (file_exists($sqLight)) {
				unlink($sqLight);
			}
		}

		public function testGetAllTables()
		{
			$tables = $this->db->getTablesList();
			$this->assertEquals('test', $tables[0], 'getAllTables');
		}

		public function testTableExists()
		{
			$table = $this->db->tableExists('TeSt');
			$this->assertEquals('test', $table, 'tableExists');
		}
	}
