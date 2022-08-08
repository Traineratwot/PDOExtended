<?php /** @noinspection GlobalVariableUsageInspection */


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\Dsn;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	class TestHelpers extends TestCase
	{

		/**
		 * @throws DsnException
		 */
		public function testInit()
		: void
		{
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost('C:\light.db');

			$a = PDOE::init($dns, [], $k);
			$this->assertTrue(isset($GLOBALS[$k]));

			$b = PDOE::init($dns, [], $k);
			$this->assertEquals($a, $b);
		}

		public function testPrepare()
		: void
		{
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

			$sql1 = "SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < :calories AND colour = :colour;;;;";
			$sql2 = '  SELECT "name", colour, calories FROM fruit WHERE calories < ? AND colour = ?;   ';
			$sql3 = "SELECT * FROM issues WHERE tag::jsonb ?? ?";

			$sql1_ = Helpers::prepare($sql1, ['calories' => 150, 'colour' => 'red'], $this->db);
			$sql2_ = Helpers::prepare($sql2, [150, 'red'], $this->db);
			$sql3_ = Helpers::prepare($sql3, [150, 'red'], $this->db);
			$sql4_ = Helpers::prepare($sql1, ['calories' => 150, 'colour' => 'red']);

			$this->assertEquals("SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < '150' AND colour = 'red';", $sql1_, 'test');
			$this->assertEquals('SELECT "name", colour, calories FROM fruit WHERE calories < \'150\' AND colour = \'red\';', $sql2_, 'test');
			$this->assertEquals("SELECT * FROM issues WHERE tag::jsonb ?? '150';", $sql3_, 'test');

			$this->assertEquals("SELECT `name`, 'colour test', calories FROM fruit WHERE `calories` < '150' AND colour = 'red';", $sql4_, 'test');

		}

		/**
		 * @throws DsnException
		 */
		public function testDsn()
		: void
		{
			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_SQLite);
			$dns->setHost('C:\light.db');
			$this->assertEquals("sqlite:C:\light.db", $dns->get(), 'sqlite');

			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_MySQL);
			$dns->setHost('localhost');
			$dns->setUsername('root');
			$dns->setPassword('');
			$dns->setPort(-1);
			$dns->setDatabase('test');
			$this->assertEquals("mysql:host=localhost;dbname=test;charset=utf8;", $dns->get(), 'sqlite');

			$dns = new Dsn();
			$dns->setDriver(PDOE::DRIVER_PostgreSQL);
			$dns->setHost('127.0.0.1');
			$dns->setUsername('postgres');
			$dns->setPassword('');
			$dns->setPort(5432);
			$dns->setDatabase('test');
			$this->assertEquals("pgsql:host=127.0.0.1;port=5432;dbname=test;", $dns->get(), 'sqlite');

			try {
				$dns = new Dsn();
				$dns->setDriver(PDOE::DRIVER_SQLite);
				$dns->setHost('C:\light.db');
				$dns->setSocket('C:\light.db');
				$this->assertEquals("sqlite:C:\light.db", $dns->get(), 'sqlite');
				$this->fail();
			} catch (DsnException $e) {
				$this->assertTrue(TRUE, $e->getMessage());
			} catch (Exception $e) {
				$this->fail($e->getMessage());
			}
		}
	}
