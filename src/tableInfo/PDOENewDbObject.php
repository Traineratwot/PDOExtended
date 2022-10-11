<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\driver;
	use Traineratwot\PDOExtended\tableInfo\dataType\TEnum;
	use Traineratwot\PDOExtended\tableInfo\dataType\TInt;
	use Traineratwot\PDOExtended\tableInfo\dataType\TString;

	class PDOENewDbObject
	{
		public Scheme  $scheme;
		public string  $table;
		public ?driver $driver    = NULL;
		public array   $columns   = [];
		public string  $engine    = 'InnoDB';
		public string  $collate   = 'utf8mb4_unicode_ci';
		public string  $comment   = '';
		public array   $keys;
		public bool    $dropTable = FALSE;

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

		public function addInt(string $name, $length = 10, $unsigned = FALSE, $canBeBull = TRUE, $default = NULL, $comment = '')
		{
			$this->columns[$name] = [
				'type'       => TInt::class, 'name' => $name, 'comment' => $comment, 'options' => [
					'length'    => $length,
					'unsigned'  => $unsigned,
					'canBeBull' => $canBeBull,
				], 'default' => $default,
			];
			return $this;
		}

		public function addString(string $name, $length = 50, $canBeBull = TRUE, $default = NULL, $comment = '')
		{
			$this->columns[$name] = [
				'type'       => TString::class, 'name' => $name, 'comment' => $comment, 'options' => [
					'length'    => $length,
					'canBeBull' => $canBeBull,
				], 'default' => $default,
			];
			return $this;
		}
		public function addEnum(string $name, $cases = [], $canBeBull = TRUE, $default = NULL, $comment = '')
		{
			$this->columns[$name] = [
				'type'       => TEnum::class, 'name' => $name, 'comment' => $comment, 'options' => [
					'cases'    => $cases,
					'canBeBull' => $canBeBull,
				], 'default' => $default,
			];
			return $this;
		}

		public function setPrimaryKey($name)
		{
			foreach ($this->columns as $key => $column) {
				$this->columns[$key]['options']['isPrimary'] = FALSE;
			}
			$this->columns[$name]['options']['isPrimary'] = TRUE;
			$this->keys['primary']                        = $name;
			return $this;
		}

		public function toSql()
		: string
		{
			$cls = $this->driver->tools['Create'];
			return (new $cls($this))->toSql();
		}

		public function dropTable(bool $drop = TRUE)
		{
			$this->dropTable = $drop;
			return $this;
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

		public function setEngine(string $engine)
		{
			$this->engine = $engine;
			return $this;
		}

		public function setCollate(string $collate)
		{
			$this->collate = $collate;
			return $this;
		}

		public function setComment(string $comment)
		{
			$this->comment = $comment;
			return $this;
		}
	}