<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\PDOE;

	class MySql extends TestCase
	{


		/**
		 * @throws DsnException
		 * @throws PDOEException
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
			$this->db->logOn();
			$this->db->execFile(__DIR__ . DIRECTORY_SEPARATOR . 'mysql.sql');
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

		/**
		 */
		public function testConnect()
		: void
		{
			$tables = $this->db->getTablesList();
			$this->assertEquals('test', $tables[0], 'Connect');
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
			$c = $this->db->query("SELECT COUNT(*) FROM test")->fetch(PDO::FETCH_COLUMN);
			$this->assertEquals(24, $c);
		}

		public function testGetScheme()
		: void
		{
			$table = $this->db->getScheme('test');
			$json  = json_encode($table->toArray(), JSON_THROW_ON_ERROR | 256 | JSON_PRETTY_PRINT);
//			file_put_contents('MySql_testGetScheme.json',$json);
			$this->assertStringEqualsFile('MySql_testGetScheme.json', $json, 'getScheme');
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

		public function testSelectLeftJoin()
		{
			$sql = $this->db->table('test_link_master')->select()
							->addColumn('id')
							->addColumn('slave', 'test_link_slave')
							->joinLeft('test_link_slave')
							->toSql()
			;
			$this->assertEquals("SELECT `test_link_master`.`id`, `test_link_slave`.`slave` FROM `test_link_master` LEFT JOIN test_link_slave ON `test_link_master`.`master` = `test_link_slave`.`id`;", $sql);
		}

		public function testUpdate()
		{
			$sql = $this->db->table('test_link_master')->update()
							->set('master', 2)
							->where(3)->end()
							->toSql()
			;
			$this->assertEquals("UPDATE `test_link_master` SET `test_link_master`.`master` = '2' WHERE `test_link_master`.`id` = '3';", $sql);
		}
	}
