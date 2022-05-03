<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Delete;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Insert;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Select;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Update;
	use Traineratwot\PDOExtended\abstracts\driver;
	use Traineratwot\PDOExtended\Helpers;

	class PDOEBdObject
	{
		public Scheme $scheme;
		public string $table;
		public driver $driver;

		public function __construct(driver $driver, string $table)
		{
			if (!$driver->tableExists($table)) {
				Helpers::warn("Table '$table' does not exist");
			}
			$this->table  = $table;
			$this->driver = $driver;
			$this->scheme = $driver->getScheme($table);
		}

		public function select()
		: Abstract_Select
		{
			$cls = $this->driver->tools['Select'];
			return (new $cls($this))->setTable($this->table);

		}

		public function update($data = [])
		: Abstract_Update
		{
			$cls    = $this->driver->tools['Update'];
			$result = (new $cls($this))->setTable($this->table);
			if (!empty($data)) {
				$result->setData($data);
			}
			return $result;

		}

		public function insert($data = [])
		: Abstract_Insert
		{
			$cls    = $this->driver->tools['Insert'];
			$result = (new $cls($this))->setTable($this->table);
			if (!empty($data)) {
				$result->setData($data);
			}
			return $result;

		}

		public function delete()
		: Abstract_Delete
		{
			$cls = $this->driver->tools['Delete'];
			return (new $cls($this))->setTable($this->table);

		}

		public function __toString()
		{
			return $this->table;
		}
	}