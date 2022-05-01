<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\builders\Delete;
	use Traineratwot\PDOExtended\abstracts\builders\Insert;
	use Traineratwot\PDOExtended\abstracts\builders\Select;
	use Traineratwot\PDOExtended\abstracts\builders\Update;
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
		: Select
		{
			return (new Select($this->driver))->setTable($this->name);

		}

		public function update()
		: Update
		{
			return (new Update($this->driver))->setTable($this->name);

		}

		public function insert()
		: Insert
		{
			return (new Insert($this->driver))->setTable($this->name);

		}

		public function delete()
		: Delete
		{
			return (new Delete($this->driver))->setTable($this->name);

		}
	}