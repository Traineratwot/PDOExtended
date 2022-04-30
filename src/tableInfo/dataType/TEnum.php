<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TEnum extends DataType
	{
		public array $values = [];

		public function validate()
		: void
		{
			if (!in_array($this->value, $this->values, TRUE)) {
				throw new DataTypeException("invalid value ");
			}
		}

		public function convert()
		: void
		{
			// TODO: Implement convert() method.
		}
	}