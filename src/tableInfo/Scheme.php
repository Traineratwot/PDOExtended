<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	class Scheme
	{

		/**
		 * @var array<Column>
		 */
		public array  $columns = [];
		public array  $links   = [];
		public string $name    = '';

		public static function __set_state($an_array)
		{
			$a = new self();
			foreach ($an_array as $name => $value) {
				if (!is_array($value)) {
					$a->$name = $value;
				}
			}
			foreach ($an_array['columns'] as $column) {
				$a->addColumn($column);
			}
			return $a;
		}

		public function addColumn(Column $column)
		: self
		{
			$name                 = $column->getName();
			$this->columns[$name] = $column;
			return $this;
		}

		public function toArray()
		: array
		{
			$res = [];
			foreach ($this->columns as $name => $column) {
				$res[$name] = $column->toArray();
			}
			return $res;
		}

		public function addLink(string $table, string $masterField, string $slaveField)
		: void
		{
			$this->links[$table] = [
				'masterField' => $masterField,
				'slaveField'  => $slaveField,
			];
		}

		public function columnExists(string $column)
		{
			return isset($this->columns[$column]);
		}

		public function getColumn($column)
		: Column
		{
			return $this->columns[$column];
		}

		/**
		 * @return Column|false
		 */
		public function getPrimaryKey()
		{
			foreach ($this->columns as $column) {
				if ($column->isPrimary()) {
					return $column;
				}
			}
			return FALSE;
		}
	}