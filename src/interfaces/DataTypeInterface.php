<?php

	namespace Traineratwot\PDOExtended\interfaces;

	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	interface DataTypeInterface
	{
		/**
		 * @param $value
		 * @return $this
		 * @throws DataTypeException
		 */
		public function set($value)
		: self;

		/**
		 * @param bool  $canBeNull
		 * @param mixed $default
		 * @return $this
		 */
		public function setDefault(bool $canBeNull, $default = NULL)
		: self;

		public function get();
	}