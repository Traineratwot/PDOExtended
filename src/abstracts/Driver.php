<?php

	namespace Traineratwot\PDOExtended\abstracts;


	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\interfaces\DriverInterface;
	use Traineratwot\PDOExtended\PDOE;

	abstract class Driver implements DriverInterface
	{
		protected PDOE $connection;
		/**
		 * php data type=> sql data types[]
		 * @var array
		 */
		public array $dataTypes;

		/**
		 * @param PDOE $connection
		 */
		public function __construct(PDOE $connection)
		{
			$this->connection = $connection;
		}

		abstract public function getTablesList()
		: array;


		/**
		 * @inheritDoc
		 * @return false|string
		 */
		public function tableExists($table)
		: string
		{
			$list = $this->getTablesList();
			$find = FALSE;
			$t    = NULL;
			foreach ($list as $t) {
				if (mb_strtolower($t) === mb_strtolower($table)) {
					$find = TRUE;
					break;
				}
			}
			return $find ? $t : FALSE;
		}


		/**
		 * @template T of DataType
		 * @param string $type
		 * @return class-string<T>
		 * @throws DataTypeException
		 */
		public function findDataType(string $type)
		: string
		{
			$type = strtolower($type);
			foreach ($this->dataTypes as $dataType => $dataTypesList) {
				foreach ($dataTypesList as $dt) {
					if (strtolower($dt) === $type) {
						return $dataType;
					}
				}
			}
			throw new DataTypeException('Unknown data type:' . $type);
		}
	}