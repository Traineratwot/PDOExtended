<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\exceptions\SqlBuildException;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Abstract_Select extends Builder
	{
		private array  $columns = [];
		private string $limit   = '';
		private string $order   = '';

		public function addColumn($column)
		{
			$this->columns[] = $this->driver->escapeColumn($column, $this->table);
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

		public function toSql()
		{
			if (count($this->columns) === 0) {
				$this->columns[] = '*';
			}
			$columns = implode(', ', $this->columns);
			$v       = [];
			if ($this->where) {
				$w   = $this->where->get();
				$v   = $this->where->getValues();
				$sql = "SELECT {$columns} FROM {$this->table} WHERE {$w} {$this->order} {$this->limit}";
			} else {
				$sql = "SELECT {$columns} FROM {$this->table} {$this->order} {$this->limit}";
			}
			return Helpers::prepare($sql, $v, $this->driver->connection);
		}
	}