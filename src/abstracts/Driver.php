<?php

	namespace Traineratwot\PDOExtended\abstracts;


	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\interfaces\DriverInterface;
	use Traineratwot\PDOExtended\PDOE;
	use Traineratwot\PDOExtended\tableInfo\PDOEBdObject;

	abstract class Driver implements DriverInterface
	{
		public string $eq        = '=';
		public string $notEq     = '<>';
		public string $greater   = '<';
		public string $greaterEq = '<=';
		public string $less      = '>';
		public string $lessEq    = '>=';
		public string $in        = 'in';
		public string $notIn     = 'not in';
		public string $and       = 'and';
		public string $or        = 'or';
		public PDOE   $connection;
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

		public function table(string $table)
		{
			return new PDOEBdObject($this, $table);
		}

		/**
		 * @inheritDoc
		 */
		public function escapeTable(string $table)
		: string
		{
			$table = trim($table, '`');
			return "`$table`";
		}

		/**
		 * @inheritDoc
		 */
		public function escapeColumn(string $column, string $table = NULL)
		: string
		{
			$column = trim($column, '`');
			if ($table) {
				return $this->escapeTable($table) . ".`$column`";
			}
			return "`$column`";
		}
	}