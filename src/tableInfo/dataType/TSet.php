<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TSet extends DataType
	{
		public string $phpName = 'string';
		public array  $values  = [];

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			//TODO make this function
//			if (!in_array($value, $this->values, TRUE)) {
//				throw new DataTypeException("invalid value ");
//			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : $value;
		}
	}