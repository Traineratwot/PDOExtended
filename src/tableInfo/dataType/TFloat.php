<?php

	namespace Traineratwot\PDOExtended\tableInfo\dataType;

	use Exception;
	use Traineratwot\PDOExtended\abstracts\DataType;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;

	class TFloat extends DataType
	{
		public string $phpName = 'float';

		/**
		 * @inheritDoc
		 */
		public function validate($value)
		: void
		{
			try{
				$value = (float)$value;
			}catch (Exception $e){
				throw new DataTypeException("invalid float",0,$e);
			}
		}

		/**
		 * @inheritDoc
		 */
		public function convert($value)
		{
			return (is_null($value) || $value === 'null') ? NULL : (float)$value;
		}
	}