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

		/**
		 * @param $an_array
		 * @return Scheme
		 */
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
			foreach ($an_array['links'] as $key => $link) {
				$a->addLink($key, ...$link);
			}
			return $a;
		}

		/**
		 * @param Column $column
		 * @return $this
		 */
		public function addColumn(Column $column)
		: self
		{
			$name                 = strtolower($column->getName());
			$this->columns[$name] = $column;
			return $this;
		}

		/**
		 * @return array
		 */
		public function toArray()
		: array
		{
			$res = [];
			foreach ($this->columns as $name => $column) {
				$res[$name] = $column->toArray();
			}
			return $res;
		}

		/**
		 * @param string $table
		 * @param string $masterField
		 * @param string $slaveField
		 * @return void
		 */
		public function addLink(string $table, string $masterField, string $slaveField)
		: void
		{
			$this->links[$table] = [
				'masterField' => $masterField,
				'slaveField'  => $slaveField,
			];
		}

		/**
		 * @param string $column
		 * @return bool
		 */
		public function columnExists(string $column)
		{
			return isset($this->columns[strtolower($column)]);
		}

		/**
		 * @param $column
		 * @return Column
		 */
		public function getColumn($column)
		{
			return $this->columns[strtolower($column)];
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