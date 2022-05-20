<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TFloat extends DataType
	{
		public string $phpName = 'float';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			if ($this->canBeNull && empty($value)) {
				return;
			}
			if ($this->canBeNull && strtolower($value) === 'null') {
				return;
			}
			if (!$this->canBeNull && is_null($value)) {
				throw new DataTypeException("can`t be null");
			}
			if (!is_numeric($value) && !is_float($value + 0) && !is_int($value + 0)) {
				throw new DataTypeException("invalid float");
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return (is_null($value) || $value === 'null') ? NULL : (float)$value;
		}
	}