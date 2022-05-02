<?php

	namespace Traineratwot\PDOExtended\abstracts;


	use Traineratwot\PDOExtended\drivers\MySQL\Delete;
	use Traineratwot\PDOExtended\drivers\MySQL\Insert;
	use Traineratwot\PDOExtended\drivers\MySQL\Join;
	use Traineratwot\PDOExtended\drivers\MySQL\Select;
	use Traineratwot\PDOExtended\drivers\MySQL\Update;
	use Traineratwot\PDOExtended\drivers\MySQL\Where;
	use Traineratwot\PDOExtended\drivers\MySQL\WherePart;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\PDOE;
	use Traineratwot\PDOExtended\tableInfo\PDOEBdObject;

	abstract class Driver
	{
		public static string $driver = '';

		public array $tools
			= [
				"Delete"    => Delete::class,
				"Insert"    => Insert::class,
				"Select"    => Select::class,
				"Update"    => Update::class,
				"Where"     => Where::class,
				"WherePart" => WherePart::class,
				"Join"      => Join::class,
			];


		public string $eq        = '=';
		public string $notEq     = '<>';
		public string $greater   = '<';
		public string $greaterEq = '<=';
		public string $less      = '>';
		public string $lessEq    = '>=';
		public string $in        = 'IN';
		public string $notIn     = 'NOT IN';
		public string $and       = 'AND';
		public string $or        = 'OR';
		public string $block     = '(';
		public string $endBlock  = ')';
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

		/**
		 *
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
		 * @inheritDoc
		 */
		public function escapeTable(string $table)
		: string
		{
			$table = trim($table, '`');
			return "`$table`";
		}

		public function closeConnection()
		: void
		{

		}
	}