<?php

	namespace Traineratwot\PDOExtended\drivers;


	use PDO;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBlob;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBool;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDate;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDatetime;
	use Traineratwot\PDOExtended\tableInfo\dataType\TFloat;
	use Traineratwot\PDOExtended\tableInfo\dataType\TInt;
	use Traineratwot\PDOExtended\tableInfo\dataType\TString;
	use Traineratwot\PDOExtended\tableInfo\dataType\TUnixTime;

	class SQLite extends Driver
	{

		public array $dataTypes
			= [
				TString::class   => ['CHAR', 'TEXT', 'VARCHAR', 'STRING', 'NONE'],
				TBool::class     => ['BOOLEAN'],
				TBlob::class     => ['BLOB'],
				TDatetime::class => ['DATETIME'],
				TDate::class     => ['DATE'],
				TInt::class      => ['INTEGER', 'INT'],
				TFloat::class    => ['DOUBLE', 'REAL', 'NUMERIC'],
				TUnixTime::class => ['TIME'],
			];

		public function getTablesList()
		: array
		{
			return $this->connection->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
		}

		/**
		 * @throws DataTypeException
		 */
		public function getScheme(string $table)
		{
			$columns = $this->connection->prepareQuery("SELECT * FROM pragma_table_info(:table)", ['table' => $table])->fetchAll(PDO::FETCH_ASSOC);
			$indexes = $this->connection->prepareQuery("SELECT * FROM pragma_index_list(:table) WHERE origin != 'pk'", ['table' => $table])->fetchAll(PDO::FETCH_ASSOC);
			foreach ($columns as $column) {
				$a         = $this->findDataType($column['type']);
				$validator = new $a();
			}
//			var_dump($indexes);
			die;

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