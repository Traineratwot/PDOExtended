<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Delete;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Insert;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Select;
	use Traineratwot\PDOExtended\abstracts\builders\Abstract_Update;
	use Traineratwot\PDOExtended\abstracts\driver;

	class PDOEBdObject
	{
		public Scheme $scheme;
		public string $name;
		public driver $driver;

		public function __construct(driver $driver, string $name)
		{
			$this->name   = $name;
			$this->driver = $driver;
			$this->scheme = $driver->getScheme($name);
		}

		public function select()
		: Abstract_Select
		{
			$cls = $this->driver->tools['Select'];
			return (new $cls($this->driver))->setTable($this->name);

		}

		public function update()
		: Abstract_Update
		{
			$cls = $this->driver->tools['Update'];
			return (new $cls($this->driver))->setTable($this->name);

		}

		public function insert()
		: Abstract_Insert
		{
			$cls = $this->driver->tools['Insert'];
			return (new $cls($this->driver))->setTable($this->name);

		}

		public function delete()
		: Abstract_Delete
		{
			$cls = $this->driver->tools['Delete'];
			return (new $cls($this->driver))->setTable($this->name);

		}
	}