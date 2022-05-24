<?php

	namespace Traineratwot\PDOExtended\abstracts;

	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	abstract class DataType
	{
		public ?string $columnName = NULL;

		public function __construct(string $columnName = '')
		{
			$this->columnName = $columnName;
		}

		/**
		 * @var mixed
		 */
		public        $value;
		public string $originalType = '';

		public string $phpName   = 'mixed';
		public bool   $canBeNull = TRUE;
		/**
		 * @var mixed|null
		 */
		public      $default;
		public bool $isSet = FALSE;

		public static function __set_state($an_array)
		{
			$cls = static::class;
			$a   = new $cls();
			foreach ($an_array as $key => $value) {
				$a->$key = $value;
			}
			return $a;
		}

		/**
		 * @param $value
		 * @return $this
		 * @throws DataTypeException
		 */
		public function set($value)
		: self
		{
			$this->isSet = TRUE;
			$this->validate($value);
			$value       = $this->convert($value);
			$this->value = $value;
			return $this;
		}

		/**
		 * validate input value
		 * @param mixed $value
		 * @return void
		 * @throws DataTypeException
		 */
		abstract public function validate($value)
		: void;

		/**
		 * if validate convert value to correct data type
		 * @param mixed $value
		 * @return mixed
		 */
		abstract public function convert($value);

		/**
		 * @param bool $canBeNull
		 * @param      $default
		 * @return $this
		 * @throws DataTypeException
		 */
		public function setDefault(bool $canBeNull, $default = NULL)
		: self
		{
			$this->canBeNull = $canBeNull;
			$this->validate($default);
			$default       = $this->convert($default);
			$this->default = $default;
			return $this;
		}

		public function get()
		{
			if ($this->isSet) {
				return $this->value;
			}
			return $this->default;
		}

		/**
		 * @return string
		 */
		public function getOriginalType()
		: string
		{
			return $this->originalType;
		}

		/**
		 * @param string $originalType
		 */
		public function setOriginalType(string $originalType)
		: void
		{
			$this->originalType = $originalType;
		}
	}