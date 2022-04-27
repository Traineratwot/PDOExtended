<?php

	namespace Traineratwot\PDOExtended\dsn;

	use Traineratwot\PDOExtended\PDOE;

	class DsnSocket extends dsn
	{

		private $socket;

		/**
		 * @param string $socket
		 * @return $this
		 */
		public function setSocket($socket)
		{
			$this->socket = $socket;
			return $this;
		}

		public function get()
		{
			if ($this->driver === PDOE::DRIVER_SQLite) {
				return $this->driver . ":" . $this->socket;
			}
			$dsn = "{$this->driver}:unix_socket={$this->socket}:{$this->port};";
			if ($this->database) {
				$dsn .= "dbname={$this->database};";
			}
			if ($this->charset) {
				$dsn .= "charset={$this->charset};";
			}
			return $dsn;
		}
	}