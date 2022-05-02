<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TBlob extends DataType
	{

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
			return is_null($value) ? NULL : $value;
		}
	}