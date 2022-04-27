<?php

	namespace Traineratwot\PDOExtended\dsn;

	abstract class dsn
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
		public $DRIVERS
						 = [
				self::DRIVER_PostgreSQL => 5432,
				self::DRIVER_MySQL      => 3306,
				self::DRIVER_SQLite     => '',
			];
		public $password = '';
		public $username = '';
		public $driver   = '';
		public $database = '';
		public $charset  = self::CHARSET_utf8;
		public $port;

		public function __construct()
		{

		}

		/**
		 * @throws DsnException
		 */
		public function validate()
		{
			if (!$this->driver) {
				throw new DsnException('"' . $driver . '" is not set');
			}
			if (!$this->port) {
				$this->port = $this->DRIVERS[$this->driver];
			}
			return $this;
		}

		/**
		 * @throws DsnException
		 */
		public function get()
		{
			return $this->validate()->_get();
		}

		/**
		 * @return string
		 */
		public function getPassword()
		{
			return $this->password;
		}

		/**
		 * @param string $password
		 * @return dsn
		 */
		public function setPassword(string $password)
		{
			$this->password = $password;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getUsername()
		{
			return $this->username;
		}

		/**
		 * @param string $username
		 * @return dsn
		 */
		public function setUsername(string $username)
		{
			$this->username = $username;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getDriver()
		{
			return $this->driver;
		}

		/**
		 * @param string $driver
		 * @return dsn
		 */
		public function setDriver(string $driver)
		{
			$this->driver = $driver;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getDatabase()
		{
			return $this->database;
		}

		/**
		 * @param string $database
		 * @return dsn
		 */
		public function setDatabase(string $database)
		{
			$this->database = $database;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getCharset()
		{
			return $this->charset;
		}

		/**
		 * @param string $charset
		 * @return dsn
		 */
		public function setCharset(string $charset)
		{
			$this->charset = $charset;
			return $this;
		}

		/**
		 * @return int
		 */
		public function getPort()
		{
			return $this->port;
		}

		/**
		 * @param int $port
		 * @return dsn
		 */
		public function setPort(int $port)
		{
			$this->port = $port;
			return $this;
		}

		/**
		 * @return string
		 */
		abstract public function _get();
	}