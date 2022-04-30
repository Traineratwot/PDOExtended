<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TInt extends DataType
	{

		public function validate()
		: void
		{
			if (($this->value && $this->canBeNull) && (!is_numeric($this->value) || !is_int($this->value + 0))) {
				throw new DataTypeException("invalid string");
			}
		}

		public function convert()
		: void
		{
			// TODO: Implement convert() method.
		}
	}