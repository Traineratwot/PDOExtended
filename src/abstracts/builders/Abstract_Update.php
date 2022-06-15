<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\exceptions\DataTypeException;
	use Traineratwot\PDOExtended\exceptions\SqlBuildException;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Update extends Builder
	{
		public array $values     = [];
		public array $columns    = [];
		public int   $i          = 0;
		public array $validators = [];

		public function setData(array $data)
		{
			foreach ($data as $column => $value) {
				$this->set($column, $value);
			}
			return $this;
		}

		/**
		 * @throws SqlBuildException
		 */
		public function set(string $column, $value)
		{

			try {
				if (!$this->scheme->columnExists($column)) {
					Helpers::warn("Column '$column' does not exist in table {$this->table}");
					return $this;
				}
				$val = $this->scheme->getColumn($column);
				$this->i++;
				$this->columns[$this->i]                 = $column;
				$this->values["PDOE_{$this->i}_upd"]     = $val->validate($value);
				$this->validators["PDOE_{$this->i}_upd"] = $val->validator;
			} catch (DataTypeException $e) {
				throw new SqlBuildException($column . ': ' . $e->getMessage(), 0, $e);
			}
			return $this;
		}

		public function toSql()
		{
			$set = [];
			foreach ($this->columns as $i => $column) {
				$set[] = $this->driver->escapeColumn($column, $this->table) . " = :PDOE_{$i}_upd";
			}
			$set = implode(', ', $set);
			if ($this->where) {
				$w            = $this->where->get($this->validators);
				$this->values = array_merge($this->values, $this->where->getValues());
				$sql          = implode('', ['UPDATE', $this->table, 'SET', $set, 'WHERE', $w]);
			} else {
				$sql = implode('', ['UPDATE', $this->table, 'SET', $set]);
			}
			$sql = preg_replace("/+/u", ' ', $sql);
			return Helpers::prepare($sql, $this->values, function ($val, $key) {
				if ($this->validators[trim($key, ':')]) {
					return $this->validators[trim($key, ':')]->escape($this->driver->connection, $val);
				}
				return Helpers::getValue($this->driver->connection, $val);
			});

		}
	}