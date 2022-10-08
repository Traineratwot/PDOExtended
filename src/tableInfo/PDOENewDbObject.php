<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\abstracts\driver;

	class PDOENewDbObject
	{
		public Scheme $scheme;
		public string $table;
		public driver $driver;
		public array  $columns = [];

		/**
		 * @param string $table
		 */
		public function __construct(string $table)
		{
			$this->table = $table;
		}

		/**
		 * @param driver $driver
		 * @return PDOENewDbObject
		 */
		public function setDriver(driver $driver)
		: PDOENewDbObject
		{
			$this->driver = $driver;
			return $this;
		}

		/**
		 * @param DataType $type
		 * @param string   $name
		 * @param array    $options
		 * @return void
		 */
		public function addColumn(dataType $type, string $name, array $options = [])
		{
			$this->columns[] = ['type' => $type, 'name' => $name, 'options' => $options];
		}

		/**
		 * @param driver|null $driver
		 * @return bool
		 */
		public function run(driver $driver = NULL)
		: bool
		{
			if ($driver) {
				$this->setDriver($driver);
			}
			if (!($this->driver instanceof Driver)) {
				return FALSE;
			}

			return TRUE;
		}

		/**
		 * @return string
		 */
		public function __toString()
		: string
		{
			return $this->table;
		}
	}