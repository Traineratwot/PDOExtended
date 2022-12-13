<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Exception;
	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\Helpers;

	class TJson extends DataType
	{
		public string $phpName = 'string';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			try {
				if (!is_array($value) && !is_object($value)) {
					Helpers::jsonValidate($value);
				}
			} catch (Exception $e) {
				throw new DataTypeException("invalid json", 0, $e);
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			if (is_null($value)) {
				return NULL;
			}
			return is_string($value) ? $value : json_encode($value);
		}
	}