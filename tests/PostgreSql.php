<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\PDOE;

	class PostgreSql extends TestCase
	{
		public function setup()
		{
			define('WT_HOST_DB', '127.0.0.1');
			define('WT_PORT_DB', '5432');
			define('WT_DATABASE_DB', 'test');
			define('WT_TYPE_DB', 'pgsql');
			define('WT_USER_DB', '');
			define('WT_PASS_DB', '');
			define('WT_CHARSET_DB', '');
			define('WT_DSN_DB', WT_TYPE_DB . ":" . WT_HOST_DB);
			$this->db = new PDOE(WT_DSN_DB, WT_USER_DB, WT_PASS_DB);
		}

		public function testConnect()
		{
			$this->assertEquals(1, 1, 'test');
		}
	}
