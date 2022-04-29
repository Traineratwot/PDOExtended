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
			$sqLight = __DIR__ . '/test.db';
			if (file_exists($sqLight)) {
				unlink($sqLight);
			}
			$f = fopen($sqLight, 'wb');
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

		/**
		 * @throws DsnException
		 */
		public function testConnect()
		{
			$tables = $this->db->getAllTables();
			$this->assertEquals('test', $tables[0], 'Connect');
		}
	}
