<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\PDOE;

	class MySql extends TestCase
	{


		/**
		 * @throws DsnException|PDOEException
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
			$this->db->exec(
				<<<SQL
DROP TABLE IF EXISTS `test`;
CREATE TABLE IF NOT EXISTS `test` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `value` VARCHAR(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
SQL

			);
		}

		public function tearDown()
		: void
		{
			echo 'queryCount:' . $this->db->queryCount() . PHP_EOL;
			echo 'queryTime:' . $this->db->queryTime() . PHP_EOL;
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
			$c=  $this->db->query("SELECT count(*) from test")->fetch(PDO::FETCH_COLUMN);
			$this->assertEquals(24, $c);
		}

		public function testGetScheme()
		: void
		{
			$table = $this->db->getScheme('test');
			$json  = json_encode($table->toArray(), JSON_THROW_ON_ERROR | 256|JSON_PRETTY_PRINT);
			$this->assertStringEqualsFile('MySql_testGetScheme.json', $json, 'getScheme');
		}
	}
