<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\exceptions\SqlBuildException;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Select extends Builder
	{
		private array  $columns    = [];
		private string $limit      = '';
		private string $order      = '';
		public array   $validators = [];

		/**
		 * @param      $column
		 * @param null $from
		 * @return $this
		 * @noinspection NestedPositiveIfStatementsInspection
		 */
		public function addColumn($column, $from = NULL)
		{
			if (is_null($from)) {
				if (!$this->scheme->columnExists($column)) {
					Helpers::warn("Column '$column' does not exist in table {$this->table}");
				}
			} else {
				if (!$this->driver->table($from)->scheme->columnExists($column)) {
					Helpers::warn("Column '$column' does not exist in table {$this->table}");
				}
			}
			$this->columns[] = $this->driver->escapeColumn($column, $from ?: $this->table);
			return $this;
		}

		public function limit(int $limit, int $offset = 0)
		{
			if ($offset) {
				$this->limit = "LIMIT $offset,$limit";
			} else {
				$this->limit = "LIMIT  $limit";
			}
			return $this;
		}

		/**
		 * @param array $orders
		 * @return Abstract_Select
		 * @throws SqlBuildException
		 */
		public function orderBy(array $orders)
		{
			if (!empty($orders)) {
				$o = [];
				foreach ($orders as $by => $direction) {
					$by        = $this->driver->escapeColumn($by, $this->table);
					$direction = strtoupper($direction);
					if ($direction !== 'ASC' && $direction !== 'DESC') {
						throw new SqlBuildException('Wrong order direction: ' . $direction);
					}
					$o[] = "{$by} {$direction}";
				}
				$this->order = 'ORDER BY ' . implode(', ', $o);
			}
			return $this;
		}

		/**
		 * @param string        $table
		 * @param callable|null $callback
		 * @return mixed|Abstract_Join|null
		 */
		public function join(string $table, ?callable $callback = NULL)
		{
			$tbl          = $this->driver->table($table);
			$cls          = $this->driver->tools['Join'];
			$join         = new $cls($this, $tbl, $callback);
			$this->join[] = $join;
			return $join;
		}

		public function joinLeft(string $table, ?string $column = NULL, ?string $leftColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->left($column, $leftColumn);
			$this->join[] = $join;
			return $this;
		}

		public function joinInner(string $table, ?string $column = NULL, ?string $innerColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->inner($column, $innerColumn);
			$this->join[] = $join;
			return $this;
		}

		public function joinRight(string $table, ?string $column = NULL, ?string $rightColumn = NULL)
		{
			$tbl  = $this->driver->table($table);
			$cls  = $this->driver->tools['Join'];
			$join = new $cls($this, $tbl);
			$join->right($column, $rightColumn);
			$this->join[] = $join;
			return $this;
		}

		public function toSql()
		{
			if (count($this->columns) === 0) {
				$this->columns[] = '*';
			}
			$columns = implode(', ', $this->columns);
			$v       = [];
			$j       = '';
			if (!empty($this->join)) {
				$j = [];
				foreach ($this->join as $join) {
					$j[] = $join->get();
				}
				$j = implode(' ', $j);
			}
			if ($this->where) {
				$w   = $this->where->get($this->validators);
				$v   = $this->where->getValues();
				$sql = implode('', ['SELECT', $columns, 'FROM', $this->table, $j, 'WHERE', $w, $this->order, $this->limit]);
			} else {
				$sql = implode('', ['SELECT', $columns, 'FROM', $this->table, $j, $this->order, $this->limit]);
			}
			$sql = preg_replace("/+/u", ' ', $sql);
			return Helpers::prepare($sql, $v, function ($val, $key) {
				if ($this->validators[trim($key, ':')]) {
					return $this->validators[trim($key, ':')]->escape($this->driver->connection, $val);
				}
				return Helpers::getValue($this->driver->connection, $val);
			});
		}
	}