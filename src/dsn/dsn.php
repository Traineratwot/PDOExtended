<?php

	namespace Traineratwot\PDOExtended\dsn;

	use Traineratwot\PDOExtended\PDOE;

	abstract class dsn
	{
		public $DRIVERS
						 = [
				PDOE::DRIVER_PostgreSQL => 5432,
				PDOE::DRIVER_MySQL      => 3306,
				PDOE::DRIVER_SQLite     => '',
			];
		public $password;
		public $username;
		public $driver   = '';
		public $database = '';
		public $charset  = PDOE::CHARSET_utf8;
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
		public function _get()
		{
			return $this->validate()->get();
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
		abstract public function get();
	}