<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\drivers\MySQL\Where;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
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
			$this->sqLight = __DIR__ . '/test.' . random_int(0, 1000) . '.db';
			file_put_contents($this->sqLight, '');
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost($this->sqLight);
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
			echo 'queryCount:' . $this->db->queryCount() . PHP_EOL;
			echo 'queryTime:' . $this->db->queryTime() . PHP_EOL;
			echo '------------------' . PHP_EOL;
			unset($this->db);
			gc_collect_cycles();
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

		/**
		 * @throws JsonException
		 */
		public function testGetScheme()
		: void
		{
			$table = $this->db->getScheme('test');
			$json  = json_encode($table->toArray(), JSON_THROW_ON_ERROR | 256 | JSON_PRETTY_PRINT);
			$this->assertStringEqualsFile('SqlLight_testGetScheme.json', $json, 'getScheme');
		}

		/**
		 * @throws Exception
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
			$c = $this->db->query("SELECT COUNT(*) FROM test")->fetch(PDO::FETCH_COLUMN);
			$this->assertEquals(24, $c);
		}

		public function testSelect()
		{
			$sql = $this->db->table('test')->select()
							->addColumn('id')
							->addColumn('value')
							->limit(1, 2)
							->orderBy([
										  'id' => "asc",
									  ])
							->where(function ($w) {
								$w->in('id', [5, 6, 8])
								  ->or()
								  ->less('id', 5)
								;
							})->end()
							->toSql()
			;
			$this->assertEquals("SELECT `test`.`id`, `test`.`value` FROM `test` WHERE `test`.`id` IN ('5','6','8') OR `test`.`id` < '5' ORDER BY `test`.`id` ASC LIMIT 2,1;", $sql);
		}

		public function testWhereCondition()
		{
			$sql = $this->db->table('test')->select()
							->where(function (Where $w) {
								$w->and(function (Where $w) {
									$w->eq('id', 5);
								});
								$w->or(function (Where $w) {
									$w->notEq('id', 8);
									$w->and();
									$w->notEq('id', 9);
								});

							})->end()
							->toSql()
			;
			$this->assertEquals("SELECT * FROM `test` WHERE ( `test`.`id` = '5' ) OR ( `test`.`id` <> '8' AND `test`.`id` <> '9' );", $sql);

		}
	}
