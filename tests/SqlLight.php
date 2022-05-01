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
			$this->db->exec("
CREATE TABLE test
(
	id    INTEGER
		CONSTRAINT test_pk
			PRIMARY KEY AUTOINCREMENT,
	value TEXT
);
");
		}

		public function tearDown()
		: void
		{
			$sqLight = __DIR__ . '/test.db';
			echo 'queryCount:' . $this->db->queryCount() . PHP_EOL;
			echo 'queryTime:' . $this->db->queryTime() . PHP_EOL;
			unset($this->db);
			gc_collect_cycles();
			if (file_exists($sqLight)) {
				unlink($sqLight);
			}
		}

		public function testGetAllTables()
		: void
		{
			$tables = $this->db->getTablesList();
			$this->assertEquals('test', $tables[0], 'getAllTables');
		}

		public function testTableExists()
		: void
		{
			$table = $this->db->tableExists('TeSt');
			$this->assertEquals('test', $table, 'tableExists');
		}

		public function testGetScheme()
		: void
		{
			$table = $this->db->getScheme('test');
			$json  = json_encode($table->toArray(), JSON_THROW_ON_ERROR | 256|JSON_PRETTY_PRINT);
			$this->assertStringEqualsFile('SqlLight_testGetScheme.json', $json, 'getScheme');
		}

		/**
		 */
		public function testPool()
		: void
		{
			$pool = $this->db->poolPrepare('INSERT INTO test (`value`)VALUES(:value)');
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->execute(['value' => random_int(0, 1000)]);
			$pool->run();
			$c=  $this->db->query("SELECT count(*) from test")->fetch(PDO::FETCH_COLUMN);
			$this->assertEquals(24, $c);
		}
	}
