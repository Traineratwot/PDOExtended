<?php

	namespace Traineratwot\PDOExtended\drivers;

	use PDO;
	use Traineratwot\Cache\Cache;
	use Traineratwot\Cache\CacheException;
	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\PDOE;
	use Traineratwot\PDOExtended\tableInfo\Column;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBlob;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBool;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDate;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDatetime;
	use Traineratwot\PDOExtended\tableInfo\dataType\TEnum;
	use Traineratwot\PDOExtended\tableInfo\dataType\TFloat;
	use Traineratwot\PDOExtended\tableInfo\dataType\TInt;
	use Traineratwot\PDOExtended\tableInfo\dataType\TSet;
	use Traineratwot\PDOExtended\tableInfo\dataType\TString;
	use Traineratwot\PDOExtended\tableInfo\dataType\TUnixTime;
	use Traineratwot\PDOExtended\tableInfo\Scheme;

	class MySQL extends Driver
	{
		public array $dataTypes
			= [
				TString::class   => ['CHAR', 'TEXT', 'VARCHAR', 'STRING', 'LONGTEXT', 'TINYTEXT', 'MEDIUMTEXT', 'BINARY', 'VARBINARY'],
				TInt::class      => ['BIT', 'TINYINT', 'SMALLINT', 'MEDIUMINT', 'INT', 'INTEGER', 'BIGINT',],
				TFloat::class    => ['DOUBLE', 'REAL', 'NUMERIC', 'DECIMAL', 'DEC', 'NUMERIC', 'FIXED', 'FLOAT', 'PRECISION'],
				TEnum::class     => ['ENUM'],
				TSet::class      => ['SET'],
				TBool::class     => ['BOOLEAN', 'BOOL'],
				TBlob::class     => ['BLOB'],
				TDatetime::class => ['DATETIME'],
				TDate::class     => ['DATE'],
				TUnixTime::class => ['TIME'],
			];

		public function getTablesList()
		: array
		{
			return Cache::call('tablesList', function () {
				return $this->connection->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
			},                 PDOE::CACHE_EXPIRATION, $this->connection->getKey());
		}

		/**
		 * @inheritDoc
		 * @throws DataTypeException|PDOEException|CacheException
		 */
		public function getScheme(string $table)
		: Scheme
		{
			if (!$this->tableExists($table)) {
				throw new PDOEException('table: "' . $table . '" is not exist');
			}
			return Cache::call('Scheme_' . $table, function () use ($table) {
				$columns = $this->connection->prepareQuery("SELECT * FROM `information_schema`.`COLUMNS` WHERE TABLE_SCHEMA=:database AND TABLE_NAME=:table ORDER BY ORDINAL_POSITION;", ['table' => $table, 'database' => $this->connection->dsn->getDatabase()])->fetchAll(PDO::FETCH_ASSOC);
				$Scheme  = new Scheme();
				foreach ($columns as $column) {
					$col = new Column();
					try {
						$a = $this->findDataType($column['DATA_TYPE']);
						/** @var DataType $validator */
						$validator = new $a();
						$validator->setOriginalType($column['DATA_TYPE']);
						$col->setCanBeNull(strtolower($column['IS_NULLABLE']) === 'yes')
							->setDbDataType($column['DATA_TYPE'])
							->setDefault($column['COLUMN_DEFAULT'])
							->setIsSetDefault(!is_null($column['COLUMN_DEFAULT']))
							->setName($column['COLUMN_NAME'])
							->setValidator($validator)
						;
						if (in_array($column['COLUMN_KEY'], ['pri', 'uni'])) {
							$col->setIsUnique();
						}
						$Scheme->addColumn($col);
					} catch (\Exception $e) {

					}
				}
				$indexes = $this->connection->prepareQuery("SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE   CONSTRAINT_SCHEMA=:database   AND TABLE_NAME=:table   AND REFERENCED_TABLE_NAME IS NOT NULL;;", ['table' => $table, 'database' => $this->connection->dsn->getDatabase()])->fetchAll(PDO::FETCH_ASSOC);
				foreach ($indexes as $index) {
					$Scheme->addLink($index['REFERENCED_TABLE_NAME'], $index['COLUMN_NAME'], $index['REFERENCED_COLUMN_NAME']);
				}
				return $Scheme;
			},                 PDOE::CACHE_EXPIRATION, $this->connection->getKey() . '/tables');
		}

	}