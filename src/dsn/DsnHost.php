<?php

	namespace Traineratwot\PDOExtended\dsn;

	use Traineratwot\PDOExtended\PDOE;

	class DsnHost extends dsn
	{
		private $host;

		public function setHost($host)
		{
			$this->host = $host;
			return $this;
		}

		public function get()
		{
			if($this->driver === PDOE::DRIVER_SQLite){
				return $this->driver . ":" . $this->host;
			}
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