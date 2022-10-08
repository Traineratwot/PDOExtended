<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Alter;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Delete;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Insert;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Select;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Update;
	use Traineratwot\PDOExtended\abstracts\driver;
	use Traineratwot\PDOExtended\Helpers;

	class PDOEDbObject
	{
		public Scheme $scheme;
		public string $table;
		public driver $driver;

		/**
		 * @param driver $driver
		 * @param string $table
		 */
		public function __construct(driver $driver, string $table)
		{
			if (!$driver->tableExists($table)) {
				Helpers::warn("Table '$table' does not exist");
			}
			$this->table  = $table;
			$this->driver = $driver;
			$this->scheme = $driver->getScheme($table);
		}

		/**
		 * @return Abstract_Select
		 */
		public function select()
		: Abstract_Select
		{
			$cls = $this->driver->tools['Select'];
			return (new $cls($this))->setTable($this->table);

		}

		/**
		 * @param array $data
		 * @return Abstract_Update
		 */
		public function update(array $data = [])
		: Abstract_Update
		{
			$cls    = $this->driver->tools['Update'];
			$result = (new $cls($this))->setTable($this->table);
			if (!empty($data)) {
				$result->setData($data);
			}
			return $result;

		}

		/**
		 * @param array $data
		 * @return Abstract_Insert
		 */
		public function insert(array $data = [])
		: Abstract_Insert
		{
			$cls    = $this->driver->tools['Insert'];
			$result = (new $cls($this))->setTable($this->table);
			if (!empty($data)) {
				$result->setData($data);
			}
			return $result;

		}

		/**
		 * @return Abstract_Delete
		 */
		public function delete()
		: Abstract_Delete
		{
			$cls = $this->driver->tools['Delete'];
			return (new $cls($this))->setTable($this->table);

		}

		public function alter()
		: Abstract_Alter
		{
			$cls = $this->driver->tools['Alter'];
			return (new $cls($this))->setTable($this->table);

		}

		/**
		 * @return string
		 */
		public function __toString()
		{
			return $this->table;
		}
	}