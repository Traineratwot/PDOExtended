<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TPoint extends DataType
	{

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

		public function escape($escape, $value)
		{
			return $value;
		}
	}