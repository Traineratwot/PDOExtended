<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Insert extends Builder
	{
		public array $values  = [];
		public array $columns = [];
		public int   $i       = 0;

		public function set(string $column, $value)
		{
			if (!$this->scheme->columnExists($column)) {
				Helpers::warn("Column '$column' does not exist in table {$this->table} ");
				return $this;
			}
			$val = $this->scheme->getColumn($column)->validate($value);
			$this->i++;
			$this->columns[$this->i] = [
				'column' => $this->driver->escapeColumn($column),
				'value'  => [
					'alias' => "val{$this->i}",
					'val'   => $val,
				],
			];
			return $this;
		}

		public function setData(array $data)
		{
			foreach ($data as $column => $value) {
				$this->set($column, $value);
			}
			return $this;
		}

		public function toSql()
		{
			$columns = [];
			$values  = [];
			$aliases = [];
			foreach ($this->columns as $i => $column) {
				$columns[$i]                       = $column['column'];
				$values[$column['value']['alias']] = $column['value']['val'];
				$aliases[$i]                       = ':'.$column['value']['alias'];
			}
			$columns = implode(', ', $columns);
			$aliases = implode(', ', $aliases);
			$sql = implode('', ['INSERT INTO', $this->table, '(', $columns, ')', 'VALUES', '(', $aliases, ')',]);
			$sql = preg_replace("/+/u", ' ', $sql);
			return Helpers::prepare($sql, $values, $this->driver->connection);
		}
	}
