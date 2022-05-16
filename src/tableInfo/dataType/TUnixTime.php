<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TUnixTime extends DataType
	{
		public string $phpName = 'int';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			// TODO: Implement validate() method.
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : $value;
		}
	}