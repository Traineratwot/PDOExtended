<?php

	namespace Traineratwot\PDOExtended\abstracts;


	use Traineratwot\PDOExtended\drivers\MySQL\Create;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\PDOE;
	use Traineratwot\PDOExtended\tableInfo\Column;
	use Traineratwot\PDOExtended\tableInfo\PDOEDbObject;

	abstract class Driver
	{
		public static string $driver    = '';
		public array         $schemes   = [];
		public array         $tools
										= [
				"Delete"    => '',
				"Insert"    => '',
				"Select"    => '',
				"Update"    => '',
				"Where"     => '',
				"WherePart" => '',
				"Join"      => '',
				"Alter"     => '',
				"Create"    => Create::class,
			];
		public string        $eq        = '=';
		public string        $notEq     = '<>';
		public string        $greater   = '<';
		public string        $greaterEq = '<=';
		public string        $less      = '>';
		public string        $lessEq    = '>=';
		public string        $in        = 'IN';
		public string        $notIn     = 'NOT IN';
		public string        $and       = 'AND';
		public string        $or        = 'OR';
		public string        $block     = '(';
		public string        $endBlock  = ')';
		public PDOE          $connection;
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

		/**
		 *
		 * @return false|string
		 */
		public function tableExists($table)
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
		 * @return array
		 */
		abstract public function getTablesList()
		: array;

		/**
		 * @template T of DataType
		 * @param string $type
		 * @return class-string<T>
		 * @throws DataTypeException
		 */
		public function findDataType(string $type)
		: string
		{
			$type_ = NULL;
			preg_match('/^(\w+)/', $type, $type_);
			if ($_type = $type_[1]) {
				foreach ($this->dataTypes as $dataType => $dataTypesList) {
					foreach ($dataTypesList as $dt) {
						if (strtolower($dt) === strtolower($_type)) {
							return $dataType;
						}
					}
				}
			}
			throw new DataTypeException('Unknown data type:' . $type);
		}

		/**
		 * @param string $table
		 * @return PDOEDbObject
		 */
		public function table(string $table)
		{
			return new PDOEDbObject($this, $table);
		}

		/**
		 * @param string      $column
		 * @param string|NULL $table
		 * @return string
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

		/**
		 * @param string $table
		 * @return string
		 */
		public function escapeTable(string $table)
		: string
		{
			$table = trim($table, '`');
			return "`$table`";
		}

		/**
		 * @return void
		 */
		public function closeConnection()
		: void
		{

		}

		public function escape(Column $column, mixed $value)
		: string
		{
			if (is_null($value) && $column->isCanBeNull()) {
				return "NULL";
			}
			return $column->getValidator()->escape($this->connection, $value);
		}
	}