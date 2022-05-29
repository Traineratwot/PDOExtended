<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Alter extends Builder
	{
		private array $add    = [];
		private array $modify = [];
		private array $drop   = [];
		private array $rename = [];

		/**
		 * @param string $column_name
		 * @param        $datatype
		 * @param null   $precision
		 * @return $this
		 */
		public function addCol(string $column_name, $datatype, $precision = NULL)
		{
			$t           = $this->getType($datatype, $precision);
			$column_name = $this->driver->escapeColumn($column_name);
			$this->add[] = "$column_name $t";
			return $this;
		}

		private function getType($datatype, $precision)
		{
			if ($precision) {
				return "$datatype($precision)";
			}

			return $datatype;
		}

		/**
		 * @param string $column_name
		 * @return $this
		 */
		public function dropCol(string $column_name)
		{
			$column_name  = $this->driver->escapeColumn($column_name);
			$this->drop[] = "DROP COLUMN $column_name";
			return $this;
		}

		/**
		 * @param string $column_name
		 * @param        $datatype
		 * @param null   $precision
		 * @return $this
		 */
		public function modifyCol(string $column_name, $datatype, $precision = NULL)
		{
			$t              = $this->getType($datatype, $precision);
			$this->modify[] = "`$column_name` $t";
			return $this;
		}

		/**
		 * @param string $column_name
		 * @param        $newName
		 * @return $this
		 */
		public function renameCol(string $column_name, $newName)
		{
			$this->rename[] = "RENAME `$column_name` TO $newName";
			return $this;
		}

		public function toSql()
		{
			$actions = "";
			if (count($this->add) > 0) {
				if (count($this->add) === 1) {
					$actions .= "ADD {$this->add[0]}";
				} else {
					$actions .= "ADD(";
					$actions .= implode(',', ($this->add));
					$actions .= ")";
				}
			}
			if (count($this->modify) > 0) {
				if (count($this->modify) === 1) {
					$actions .= "MODIFY {$this->modify[0]}";
				} else {
					$actions .= "MODIFY (";
					$actions .= implode(',', ($this->modify));
					$actions .= ")";
				}
			}
			if (count($this->drop) > 0) {
				$actions .= implode(',', ($this->drop));
			}
			if (count($this->rename) > 0) {
				$actions .= implode(',', ($this->rename));
			}
			$sql = implode('', ['ALTER TABLE', $this->table, $actions]);
			$sql = preg_replace("/+/u", ' ', $sql);
			return Helpers::prepare($sql, [], $this->driver->connection);
		}
	}