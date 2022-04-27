<?php

	namespace Traineratwot\PDOExtended\dsn;

	class Host extends dsn
	{
		private $host;

		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}

		public function get()
		{
			$dsn = "{$this->driver}:host={$this->host}:{$this->port};";
			if ($this->database) {
				$dsn .= "dbname={$this->database};";
			}
			if ($this->charset) {
				$dsn .= "charset={$this->charset};";
			}
			return $dsn;
		}
	}