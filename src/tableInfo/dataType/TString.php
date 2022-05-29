<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Exception;
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
			if (is_array($value) || is_object($value)) {
				throw new DataTypeException("invalid string");
			}
			try {
				$value = (string)$value;
			} catch (Exception $e) {
				throw new DataTypeException("invalid string", 0, $e);
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : (string)$value;
		}
	}