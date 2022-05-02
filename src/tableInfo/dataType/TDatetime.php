<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TDatetime extends DataType
	{
		public string $phpName = 'string';

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
			return is_null($value)?null: $value;
		}
	}