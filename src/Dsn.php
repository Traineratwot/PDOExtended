<?php

	namespace Traineratwot\PDOExtended;

	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\interfaces\DsnInterface;

	class Dsn implements DsnInterface
	{
		public array   $DRIVERS
						  = [
				PDOE::DRIVER_PostgreSQL => 5432,
				PDOE::DRIVER_MySQL      => 3306,
				PDOE::DRIVER_SQLite     => '',
			];
		private string $password='';
		private string $username='';
		private        $host;
		private        $socket;
		private string $driver   = '';
		private string $database = '';
		private string $charset  = PDOE::CHARSET_utf8;
		private int    $port;

		public function toArray()
		: array
		{
			return [
				'password' => $this->password,
				'username' => $this->username,
				'host'     => $this->host,
				'socket'   => $this->socket,
				'driver'   => $this->driver,
				'database' => $this->database,
				'charset'  => $this->charset,
				'port'     => $this->port,
			];
		}

		/**
		 * @return Dsn
		 */
		private function validate()
		: Dsn
		{
			return $this;
		}

		/**
		 * @return string
		 * @throws DsnException
		 */
		public function get()
		: string
		{
			return $this->validate()->_getDsn();
		}

		/**
		 * @return string
		 * @throws DsnException
		 */
		private function _getDsn()
		: string
		{
			if ($this->host) {
				//вызов метода по имени метода
				return [$this, $this->getDriver() . '_host']();
			}
			return [$this, $this->getDriver() . '_socket']();
		}

// 		dsn builders

		/**
		 * @throws DsnException
		 */
		private function pgsql_host()
		: string
		{
			$dsn = "{$this->getDriver()}:host={$this->getHost()};port={$this->getPort()};";
			if ($this->database) {
				$dsn .= "dbname={$this->getDatabase()};";
			}
			return $dsn;
		}

		/**
		 * @throws DsnException
		 */
		private function pgsql_socket()
		: string
		{
			$dsn = "{$this->getDriver()}:unix_socket={$this->getSocket()};";
			if ($this->database) {
				$dsn .= "dbname={$this->getDatabase()};";
			}
			return $dsn;
		}

		/**
		 * @throws DsnException
		 */
		private function sqlite_host()
		: string
		{
			return $this->getDriver() . ":" . $this->getHost();
		}

		/**
		 * @throws DsnException
		 */
		private function sqlite_socket()
		: string
		{
			return $this->getDriver() . ":" . $this->getSocket();
		}

		/**
		 * @throws DsnException
		 */
		private function mysql_host()
		: string
		{
			$dsn = "{$this->getDriver()}:host={$this->getHost()}:{$this->getPort()};";
			if ($this->database) {
				$dsn .= "dbname={$this->getDatabase()};";
			}
			if ($this->charset) {
				$dsn .= "charset={$this->getCharset()};";
			}
			return $dsn;
		}

		/**
		 * @throws DsnException
		 */
		private function mysql_socket()
		: string
		{
			$dsn = "{$this->getDriver()}:unix_socket={$this->getSocket()};";
			if ($this->database) {
				$dsn .= "dbname={$this->getDatabase()};";
			}
			if ($this->charset) {
				$dsn .= "charset={$this->getCharset()};";
			}
			return $dsn;
		}


//		setters and getters

		/**
		 * @return string
		 */
		public function getPassword()
		: string
		{
			return $this->password;
		}

		/**
		 * @param string $password
		 * @return dsn
		 */
		public function setPassword(string $password)
		: Dsn
		{
			$this->password = $password;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getUsername()
		: string
		{
			return $this->username;
		}

		/**
		 * @param string $username
		 * @return dsn
		 */
		public function setUsername(string $username)
		: Dsn
		{
			$this->username = $username;
			return $this;
		}

		/**
		 * @return string
		 * @throws DsnException
		 */
		public function getDriver()
		: string
		{
			if (is_null($this->charset)) {
				throw new DsnException('"driver" is not set');
			}
			if (
				!in_array($this->driver, [
					PDOE::DRIVER_PostgreSQL,
					PDOE::DRIVER_MySQL,
					PDOE::DRIVER_SQLite,
				],        TRUE)
			) {
				throw new DsnException('Driver "' . $this->driver . '" is unknown');
			}
			return $this->driver;
		}

		/**
		 * @param string $driver
		 * @return dsn
		 */
		public function setDriver(string $driver)
		: Dsn
		{
			$this->driver = $driver;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getDatabase()
		: string
		{
			return $this->database;
		}

		/**
		 * @param string $database
		 * @return dsn
		 */
		public function setDatabase(string $database)
		: Dsn
		{
			$this->database = $database;
			return $this;
		}

		/**
		 * @return string
		 */
		public function getCharset()
		: string
		{
			return $this->charset;
		}

		/**
		 * @param string $charset
		 * @return dsn
		 */
		public function setCharset(string $charset)
		: Dsn
		{
			$this->charset = $charset;
			return $this;
		}

		/**
		 * @return int
		 * @throws DsnException
		 */
		public function getPort()
		: int
		{
			if (is_null($this->port)) {
				return $this->DRIVERS[$this->getDriver()];
			}
			return $this->port;
		}

		/**
		 * @param int $port
		 * @return dsn
		 */
		public function setPort(int $port)
		: Dsn
		{
			$this->port = $port;
			return $this;
		}

		/**
		 * @return mixed
		 * @throws DsnException
		 */
		public function getHost()
		{
			if (is_null($this->host)) {
				throw new DsnException('"host" is not set');
			}
			return $this->host;
		}

		/**
		 * @param mixed $host
		 * @throws DsnException
		 */
		public function setHost($host)
		: void
		{
			if (!is_null($this->socket)) {
				throw new DsnException('You must`t set "host" and "socket" at same time');
			}
			$this->host = $host;
		}

		/**
		 * @return mixed
		 * @throws DsnException
		 */
		public function getSocket()
		{
			if (is_null($this->socket)) {
				throw new DsnException('"socket" is not set');

			}
			return $this->socket;
		}

		/**
		 * @param mixed $socket
		 * @throws DsnException
		 */
		public function setSocket($socket)
		: void
		{
			if (!is_null($this->host)) {
				throw new DsnException('You must`t set "socket" and "host" at same time');
			}
			$this->socket = $socket;
		}
	}