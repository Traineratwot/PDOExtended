<?php

	namespace Traineratwot\PDOExtended\drivers;


	use Exception;
	use PDO;
	use Traineratwot\Cache\Cache;
	use Traineratwot\Cache\CacheException;
	use Traineratwot\config\Config;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\drivers\SQLite\Alter;
	use Traineratwot\PDOExtended\drivers\SQLite\Create;
	use Traineratwot\PDOExtended\drivers\SQLite\Delete;
	use Traineratwot\PDOExtended\drivers\SQLite\Insert;
	use Traineratwot\PDOExtended\drivers\SQLite\Join;
	use Traineratwot\PDOExtended\drivers\SQLite\Select;
	use Traineratwot\PDOExtended\drivers\SQLite\Update;
	use Traineratwot\PDOExtended\drivers\SQLite\Where;
	use Traineratwot\PDOExtended\drivers\SQLite\WherePart;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\tableInfo\Column;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBlob;
	use Traineratwot\PDOExtended\tableInfo\dataType\TBool;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDate;
	use Traineratwot\PDOExtended\tableInfo\dataType\TDatetime;
	use Traineratwot\PDOExtended\tableInfo\dataType\TFloat;
	use Traineratwot\PDOExtended\tableInfo\dataType\TInt;
	use Traineratwot\PDOExtended\tableInfo\dataType\TString;
	use Traineratwot\PDOExtended\tableInfo\dataType\TUnixTime;
	use Traineratwot\PDOExtended\tableInfo\Scheme;

	;

	class SQLite extends Driver
	{
		public static string $driver = 'sqlite';
		public static string $port   = '';
		public array         $tools
			= [
				"Delete"    => Delete::class,
				"Insert"    => Insert::class,
				"Select"    => Select::class,
				"Update"    => Update::class,
				"Where"     => Where::class,
				"WherePart" => WherePart::class,
				"Join"      => Join::class,
				"Alter"     => Alter::class,
				"Create"    => Create::class,
			];
		/**
		 * @var array|string[][]
		 */
		public array $dataTypes
			= [
				TString::class   => ['CHAR', 'TEXT', 'VARCHAR', 'STRING', 'NONE'],
				TBool::class     => ['BOOLEAN'],
				TBlob::class     => ['BLOB'],
				TDatetime::class => ['DATETIME'],
				TDate::class     => ['DATE'],
				TInt::class      => ['INTEGER', 'INT'],
				TFloat::class    => ['DOUBLE', 'REAL', 'NUMERIC', 'DECIMAL'],
				TUnixTime::class => ['TIME'],
			];

		/**
		 * @param bool $cache
		 * @return array
		 * @throws CacheException
		 */
		public function getTablesList(bool $cache = TRUE)
		: array
		{
			if($cache) {
				return Cache::call('tablesList', function () {
					return $this->connection->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
				},                 Config::get('CACHE_EXPIRATION', 'PDOE', 600), $this->connection->getKey());
			}
			return $this->connection->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
		}

		/**
		 * @throws CacheException|PDOEException
		 */
		public function getScheme(string $table)
		: Scheme
		{
			if (isset($this->schemes[$table])) {
				return $this->schemes[$table];
			}
			$this->schemes[$table] = Cache::call('Scheme_' . $table, function () use ($table) {
				if (!$this->tableExists($table)) {
					throw new PDOEException('table: "' . $table . '" is not exist');
				}
				$Helpers    = Helpers::class;
				$columns    = $this->connection->prepareQuery("SELECT * FROM pragma_table_info(:table)", ['table' => $table])->fetchAll(PDO::FETCH_ASSOC);
				$indexes_db = $this->connection->prepareQuery("SELECT * FROM pragma_index_list(:table) WHERE origin != 'pk'", ['table' => $table])->fetchAll(PDO::FETCH_ASSOC);
				$links_db   = $this->connection->prepareQuery("SELECT * FROM pragma_foreign_key_list(:table);", ['table' => $table])->fetchAll(PDO::FETCH_ASSOC);
				$indexes    = [];
				foreach ($indexes_db as $index) {
					$ind                   = $this->connection->prepareQuery("SELECT * FROM pragma_index_info(:index)", ['index' => $index['name']])->fetch(PDO::FETCH_ASSOC);
					$ind                   = array_map($Helpers . '::strtolower', $ind);
					$indexes[$ind['name']] = $ind;
				}

				$Scheme       = new Scheme();
				$Scheme->name = $table;
				foreach ($columns as $column) {
					$column = array_map($Helpers . '::strtolower', $column);
					$col    = new Column();
					try {
						$a         = $this->findDataType($column['type'] ?: 'string');
						$validator = new $a();
						$col->setValidator($validator)
							->setCanBeNull(!$column['notnull'])
							->setDbDataType($column['type'])
							->setDefault($column['dflt_value'])
							->setIsSetDefault(!is_null($column['dflt_value']))
							->setName($column['name'])
						;
						if (array_key_exists($column['name'], $indexes)) {
							$col->setIsUnique();
						}
						$Scheme->addColumn($col);
					} catch (Exception $e) {

					}
				}
				foreach ($links_db as $link) {
					$Scheme->addLink($link['table'], $link['from'], $link['to']);
				}

				return $Scheme;
			},                                   Config::get('CACHE_EXPIRATION', 'PDOE', 600), $this->connection->getKey() . '/tables');
			return $this->schemes[$table];
		}

		/**
		 * @inheritDoc
		 */
		public function escapeColumn(string $column, string $table = NULL)
		: string
		{
			$column = trim($column, '`');
			return "`$column`";
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