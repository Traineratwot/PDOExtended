<?php


	use PHPUnit\Framework\TestCase;
	use Traineratwot\PDOExtended\PDOE;

	class SqlLight extends TestCase
	{
		public function setUp(): void
		{
			parent::setUp();
			define('WT_HOST_DB', 'C:\light.db');
			define('WT_PORT_DB', '');
			define('WT_DATABASE_DB', '');
			define('WT_TYPE_DB', PDOE::SQLite);
			define('WT_USER_DB', '');
			define('WT_PASS_DB', '');
			define('WT_CHARSET_DB', '');
			define('WT_DSN_DB', WT_TYPE_DB . ":" . WT_HOST_DB);
			$this->db = new PDOE(WT_DSN_DB, WT_USER_DB, WT_PASS_DB);
		}

		public function testConnect()
		{
			$tables = $this->db->getAllTables();
			$this->assertEquals('test', $tables[0], 'test');
		}
	}
