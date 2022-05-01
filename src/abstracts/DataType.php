<?php

	namespace Traineratwot\PDOExtended\abstracts;

	abstract class DataType
	{
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
		 * @inheritDoc
		 * @param $value
		 * @return $this
		 */
		public function set($value)
		: self
		{
			$this->value = $value;
			$this->isSet = TRUE;
			$this->validate();
			$this->convert();
			return $this;
		}

		/**
		 * validate input value
		 * @return void
		 */
		abstract public function validate()
		: void;

		/**
		 * if validate convert value to correct data type
		 * @return void
		 */
		abstract public function convert()
		: void;

		/**
		 * @param bool $canBeNull
		 * @param      $default
		 * @return $this
		 */
		public function setDefault(bool $canBeNull, $default = NULL)
		: self
		{
			$this->canBeNull = $canBeNull;
			$this->default   = $default;
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