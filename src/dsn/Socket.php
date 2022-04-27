<?php

	namespace Traineratwot\PDOExtended\dsn;

	class Socket extends dsn
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