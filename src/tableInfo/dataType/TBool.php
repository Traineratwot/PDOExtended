<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Exception;
	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TBool extends DataType
	{
		public string $phpName = 'bool';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			if (is_array($value) || is_object($value)) {
				throw new DataTypeException("invalid bool");
			}
			try {
				$value = (bool)$value;
			} catch (Exception $e) {
				throw new DataTypeException("invalid bool", 0, $e);
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : (bool)$value;
		}

		public function escape($escape, $value)
		{
			return $value;
		}
	}