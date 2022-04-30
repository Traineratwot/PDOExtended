<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	class Scheme
	{

		public array $columns;

		public function addColumn(Column $column)
		: self
		{
			$name                 = $column->getName();
			$this->columns[$name] = $column;
			return $this;
		}

		public static function __set_state($an_array)
		{
			$a = new self();
			foreach ($an_array['columns'] as $column) {
				$a->addColumn($column);
			}
			return $a;
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
	}