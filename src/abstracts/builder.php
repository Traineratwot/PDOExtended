<?php

	namespace Traineratwot\PDOExtended\abstracts;

	use Traineratwot\PDOExtended\abstracts\builders\Where;

	abstract class builder
	{
		public string $table = '';
		public Driver $driver;
		public ?Where $where = NULL;

		public function __construct(Driver $driver)
		{
			$this->driver = $driver;
		}

		/**
		 * @param string $table
		 * @return $this
		 */
		public function setTable(string $table)
		: builder
		{
			$this->table = $this->driver->escapeTable($table);
			return $this;
		}

		public function where($callback = NULL)
		: Where
		{
			$this->where = new Where($this, $this->driver, $callback);
			return $this->where;
		}

		abstract public function toSql();
	}