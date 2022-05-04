<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TInt extends DataType
	{
		public string $phpName = 'int';
		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			if (($value && $this->canBeNull) && (!is_numeric($value) || !is_int($value + 0))) {
				throw new DataTypeException("invalid string");
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : (int)$value;
		}
	}