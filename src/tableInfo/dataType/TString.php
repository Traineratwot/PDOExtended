<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TString extends DataType
	{

		public function validate()
		: void
		{
			if(($this->value && $this->canBeNull) && !is_string($this->value)){
				throw new DataTypeException("invalid string");
			}
		}

		public function convert()
		: void
		{
			// TODO: Implement convert() method.
		}
	}