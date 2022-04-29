<?php

	namespace Traineratwot\PDOExtended\drivers;


	use PDO;
	use Traineratwot\PDOExtended\abstracts\Driver;

	class SQLite extends Driver
	{
		public function getTablesList()
		: array
		{
			return $this->connection->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
		}
	}

	//	/**
	//	 * @return array
	//	 * @throws DsnException
	//	 */
	//	public function getTablesList()
	//	{
	//		if ($this->dsn->getDriver() === self::DRIVER_SQLite) {
	//		}
	//		if ($this->dsn->getDriver() === self::DRIVER_PostgreSQL) {
	//			return $this->query("SELECT table_name FROM information_schema.tables")->fetchAll(PDO::FETCH_COLUMN);
	//		}
	//		return $this->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
	//	}