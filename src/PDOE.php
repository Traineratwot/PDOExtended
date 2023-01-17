<?php

	namespace Traineratwot\PDOExtended;

	use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
	use PDO;
	use PDOException;
	use PDOStatement;
	use ReturnTypeWillChange;
	use Traineratwot\Cache\Cache;
	use Traineratwot\Cache\CacheException;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\drivers\MySQL;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\exceptions\PDOEException;
	use Traineratwot\PDOExtended\interfaces\DsnInterface;
	use Traineratwot\PDOExtended\statement\PDOEPoolStatement;
	use Traineratwot\PDOExtended\statement\PDOEStatement;
	use Traineratwot\PDOExtended\tableInfo\PDOEDbObject;
	use Traineratwot\PDOExtended\tableInfo\PDOENewDbObject;
	use Traineratwot\PDOExtended\tableInfo\Scheme;

	/**
	 * @implements Driver
	 */
	class PDOE extends PDO
	{
		/**
		 * PostgreSQL
		 * <img src="https://wiki.postgresql.org/images/3/30/PostgreSQL_logo.3colors.120x120.png" width="50" height="50" />
		 */
		public const DRIVER_PostgreSQL = 'pgsql';
		/**
		 * SQLite
		 * <img src="https://cdn.icon-icons.com/icons2/2699/PNG/512/sqlite_logo_icon_169724.png" width="50" height="50" />
		 */
		public const DRIVER_SQLite = 'sqlite';
		/**
		 * PostgreSQL
		 * <img src="https://img-blog.csdnimg.cn/20200828185219514.jpg?x-oss-process=image/resize,m_fixed,h_64,w_64" width="50" height="50" />
		 */
		public const DRIVER_MySQL    = 'mysql';
		public const CHARSET_utf8    = 'utf8';
		public const CHARSET_utf8mb4 = 'utf8mb4';
		/**
		 * @var dsn
		 */
		public      $dsn;
		private int $query_count = 0;
		/**
		 * All query time in microseconds
		 * @var int
		 */
		private int $query_time = 0;
		/**
		 * @var Driver
		 */
		private Driver $driver;
		private string $key;
		/**
		 * @var string
		 */
		private string $logClass   = Log::class;
		private bool   $LogEnabled = TRUE;

		/**
		 * @inheritDoc
		 * @param Dsn   $dsn
		 * @param array $driverOptions
		 * @throws DsnException
		 */
		public function __construct(DsnInterface $dsn, $driverOptions = [])
		{
			$this->dsn = $dsn;
			if (array_key_exists('LogEnabled', $driverOptions)) {
				$this->LogEnabled = (bool)$driverOptions['logClass'];
			}
			if (array_key_exists('logClass', $driverOptions)) {
				$this->logClass = $driverOptions['logClass'];
			}
			try {
				parent::__construct($dsn->get(), $dsn->getUsername(), $dsn->getPassword(), $driverOptions);
			} catch (PDOException $e) {
				throw new DsnException($dsn->get(), $e->getCode(), $e);
			}
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [PDOEStatement::class, [$this]]);
			$driverClass = $dsn->getDriverClass();
			if (!class_exists($driverClass)) {
				Helpers::warn('Invalid driver class: ' . $driverClass);
				$driverClass = MySQL::class;
			}
			$this->driver = new $driverClass($this);
			$this->key    = 'PDOE_' . Cache::getKey([$this->dsn->get(), $driverOptions]);
		}

		/**
		 * @return string
		 */
		public function getKey()
		: string
		{
			return $this->key;
		}

		/**
		 * (PHP 5 &gt;= 5.1.0, PHP 7, PECL pdo &gt;= 0.2.1)<br/>
		 * Quotes a string for use in a query.
		 * @link https://php.net/manual/en/pdo.quote.php
		 * @param mixed $string <p>
		 *                      The string to be quoted.
		 *                      </p>
		 * @param ?int  $type   [optional] <p>
		 *                      Provides a data type hint for drivers that have alternate quoting styles.
		 *                      </p>
		 * @return string|false a quoted string that is theoretically safe to pass into an
		 *                      SQL statement. Returns <b>FALSE</b> if the driver does not support quoting in
		 *                      this way.
		 */
		#[ReturnTypeWillChange] public function quote($string, $type = NULL)
		{
			if (is_null($type)) {
				if (is_numeric($string)) {
					$type = self::PARAM_INT;
				} else {
					$type = self::PARAM_STR;
				}
			}
			if (is_array($string)) {
				foreach ($string as $key => $val) {
					$string[$key] = parent::quote($val);
				}
				return implode(',', $string);
			}
			return parent::quote($string, $type);
		}

		/**
		 * prepare adn run sql query, safe
		 * @param       $sql
		 * @param array $values
		 * @return false|PDOStatement
		 */
		public function prepareQuery($sql, array $values = [])
		: false|PDOEStatement
		{
			$sql = Helpers::prepare($sql, $values, $this);
			$arg = func_get_args();
			$arg = array_slice($arg, 2);
			return $this->query($sql, ...$arg);
		}

		/**
		 * @inheritDoc
		 */
		#[ReturnTypeWillChange]
		public function prepare($query, $options = [])
		: false|PDOEStatement
		{
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [PDOEStatement::class, [$this]]);
			return parent::prepare($query, $options);
		}

		#[PhpStormStubsElementAvailable('8.0')]
		#[\ReturnTypeWillChange]
		public function query($statement, $mode = PDO::FETCH_ASSOC, ...$fetch_mode_args)
		: false|PDOEStatement
		{
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [PDOEStatement::class, [$this]]);
			$this->queryCountIncrement();
			$tStart = microtime(TRUE);
			$this->log($statement);
			$return = parent::query($statement, $mode, ...$fetch_mode_args);
			$this->queryTimeIncrement(microtime(TRUE) - $tStart);
			return $return;
		}
//---------------------------------------------- PDO -------------------------------------------------

		/**
		 * @return void
		 */
		public function queryCountIncrement()
		: void
		{
			$this->query_count++;
		}

		public function log($sql)
		{
			if ($this->LogEnabled) {
				$logClass = $this->logClass;
				($logClass::init())->log($this, $sql);
			}
			return NULL;
		}

		/**
		 * @param DsnInterface $dsn
		 * @param array        $driverOptions
		 * @param string|null  $var return global variable name
		 * @return self
		 * @throws DsnException
		 */
		public static function init(DsnInterface $dsn, array $driverOptions = [], ?string &$var = '')
		: PDOE
		{
			$var = 'PDOE_' . Cache::getKey([$dsn->get(), $driverOptions]);
			global $$var;
			if (!isset($$var) || is_null($$var)) {
				$$var = new self($dsn, $driverOptions);
			}
			return $$var;
		}

		/**
		 * @param $t
		 * @return void
		 */
		public function queryTimeIncrement(int|float $t)
		: void
		{
			$this->query_time += round(abs($t * 1000));
		}

		/**
		 * @param $filepath
		 * @return false|PDOStatement
		 * @throws PDOEException
		 */
		public function queryFile($filepath)
		: false|PDOEStatement
		{
			if (!file_exists($filepath)) {
				throw new PDOEException('file "' . $filepath . '" is not exist');
			}
			$statement = file_get_contents($filepath);
			return $this->query($statement);
		}

		/**
		 * @param $filepath
		 * @return false|int
		 * @throws PDOEException
		 */
		public function execFile($filepath)
		: false|int
		{
			if (!file_exists($filepath)) {
				throw new PDOEException('file "' . $filepath . '" is not exist');
			}
			$statement = file_get_contents($filepath);
			return $this->exec($statement);
		}

		/**
		 * @inheritDoc
		 */
		#[ReturnTypeWillChange] public function exec($statement)
		{
			$this->queryCountIncrement();
			$tStart = microtime(TRUE);
			$this->log($statement);
			$return = parent::exec($statement);
			$this->queryTimeIncrement(microtime(TRUE) - $tStart);
			return $return;
		}
//---------------------------------------------- PDO -------------------------------------------------
//----------------------------------------- opportunities --------------------------------------------

		/**
		 * @param string $statement SQL request
		 * @param array  $driver_options
		 * @return bool|PDOEPoolStatement
		 */
		public function poolPrepare(string $statement, array $driver_options = [])
		: false|PDOEPoolStatement
		{
			$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, [PDOEPoolStatement::class, [$this]]);
			return parent::prepare($statement, $driver_options);
		}

		/**
		 * Enable logging
		 * @return void
		 */
		public function logOn()
		{
			$this->LogEnabled = TRUE;
		}

		/**
		 * Disable logging
		 * @return void
		 */
		public function logOff()
		{
			$this->LogEnabled = FALSE;
		}

		/**
		 * return list all tables in database
		 * @return array
		 * @throws CacheException
		 */
		public function getTablesList()
		: array
		{
			return $this->driver->getTablesList();
		}

		/**
		 * @param string $table
		 * @return false|string|null
		 */
		public function tableExists(string $table)
		{
			return $this->driver->tableExists($table);
		}

		/**
		 * @param string $table
		 * @return PDOEDbObject
		 */
		public function table(string $table)
		{
			return $this->driver->table($table);
		}

		public function getScheme(string $table)
		: Scheme
		{
			return $this->driver->getScheme($table);
		}

		/**
		 * @param string $name
		 * @return array|false|int|null
		 */
		public function __get(string $name)
		{
			switch ($name) {
				case 'query_count':
				case 'queryCount':
					return $this->queryCount();
				case 'query_time':
				case 'queryTime':
					return $this->queryTime();
			}
			return NULL;
		}

		/**
		 * @param string $name
		 * @param        $value
		 * @return false
		 */
		public function __set(string $name, $value)
		{
			return FALSE;
		}
//----------------------------------------- opportunities --------------------------------------------
//-------------------------------------------- magick ------------------------------------------------

		/**
		 * @return int
		 */
		public function queryCount()
		: int
		{
			return $this->query_count;
		}

		/**
		 * @return int
		 */
		public function queryTime()
		: int
		{
			return $this->query_time;
		}

		/**
		 * @param string $name
		 * @return bool
		 */
		public function __isset(string $name)
		{
			return $name === 'query_count';
		}

		/**
		 * @throws PDOEException
		 */
		public function __call(string $name, array $arguments = [])
		{
			if (method_exists($this->driver, $name)) {
				return $this->driver->{$name}(...$arguments);
			}
			throw new PDOEException("Method $name is not exists");
		}

		public function __destruct()
		{
			$this->driver->closeConnection();
		}
//-------------------------------------------- magick ------------------------------------------------
//------------------------------------------- getters ------------------------------------------------
		/**
		 * @return Driver
		 */
		public function getDriver()
		{
			return $this->driver;
		}

		/**
		 * @return string
		 */
		public function getLogClass()
		{
			return $this->logClass;
		}

		/**
		 * @return dsn
		 */
		public function getDsn()
		{
			return $this->dsn;
		}
//------------------------------------------- getters ------------------------------------------------
//------------------------------------------- create ------------------------------------------------
		public function newTable(string $table)
		{
			return (new PDOENewDbObject($table))->setDriver($this->driver);
		}

		public static function createTable(string $table)
		{
			return new PDOENewDbObject($table);
		}
//------------------------------------------- create ------------------------------------------------
	}

