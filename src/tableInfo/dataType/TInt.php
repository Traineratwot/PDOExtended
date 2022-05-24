<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Exception;
	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TInt extends DataType
	{
		public string $phpName = 'int';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			try{
				$value = (int)$value;
			}catch (Exception $e){
				throw new DataTypeException("invalid int",0,$e);
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return (is_null($value) || $value === 'null') ? NULL : (int)$value;
		}
	}