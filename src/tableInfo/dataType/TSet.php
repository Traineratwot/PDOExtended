<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class TSet extends DataType
	{
		public array $values = [];

		public function validate()
		: void
		{
			//TODO make this function
//			if (!in_array($this->value, $this->values, TRUE)) {
//				throw new DataTypeException("invalid value ");
//			}
		}

		public function convert()
		: void
		{
			// TODO: Implement convert() method.
		}
	}