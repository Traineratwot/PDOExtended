<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TFloat extends DataType
	{
		public string $phpName = 'float';

		/**
		 * @inheritDoc
		 */
		public function validate()
		: void
		{
			// TODO: Implement validate() method.
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return is_null($value) ? NULL : (float)$value;
		}
	}