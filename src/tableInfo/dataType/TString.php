<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TString extends DataType
	{
		public string $phpName = 'string';
		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			if (($value && $this->canBeNull) && !is_string($value)) {
				throw new DataTypeException("invalid string");
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : $value;
		}
	}