<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\exceptions\SqlBuildException;
	use Traineratwot\PDOExtended\tableInfo\PDOEBdObject;

	abstract class Abstract_Join
	{
		public ?string      $type = NULL;
		public Driver       $driver;
		public builder      $scope;
		public PDOEBdObject $joinTable;
		public string       $column;
		public string       $column2;

		public function __construct(Builder $scope, PDOEBdObject $joinTable, $callback = NULL)
		{
			$this->driver    = $scope->driver;
			$this->scope     = $scope;
			$this->joinTable = $joinTable;

			if (is_callable($callback)) {
				$callback($this);
			}
		}

		/**
		 * @throws SqlBuildException
		 */
		public function left(?string $column = NULL, ?string $leftColumn = NULL)
		{
			if (!empty($this->type)) {
				throw new SqlBuildException("you can't use '{$this->type}' and 'LEFT' at the same time");
			}
			$this->_join($column, $leftColumn);
			$this->type = "LEFT";
			return $this->scope;
		}

		/**
		 * @throws SqlBuildException
		 */
		public function _join($column = NULL, $column2 = NULL)
		{
			if (is_null($column)) {
				foreach ($this->scope->scheme->links as $tblName => $link) {
					if ($this->joinTable->table === $tblName) {
						$this->_join($link['masterField'], $link['slaveField']);
						return;
					}
				}
				throw new SqlBuildException("Missing join field and unknown sql link");
			}
			$this->column  = $this->driver->escapeColumn($column, $this->scope->table);
			$this->column2 = $this->driver->escapeColumn($column2, $this->joinTable->table);
		}

		/**
		 * @throws SqlBuildException
		 */
		public function inner(?string $column = NULL, ?string $innerColumn = NULL)
		{
			if (!empty($this->type)) {
				throw new SqlBuildException("you can't use '{$this->type}' and 'INNER' at the same time");
			}
			$this->_join($column, $innerColumn);
			$this->type = "INNER";
			return $this->scope;
		}

		/**
		 * @throws SqlBuildException
		 */
		public function right(?string $column = NULL, ?string $rightColumn = NULL)
		{
			if (!empty($this->type)) {
				throw new SqlBuildException("you can't use '{$this->type}' and 'RIGHT' at the same time");
			}
			$this->_join($column, $rightColumn);
			$this->type = "RIGHT";
			return $this->scope;
		}

		public function end()
		{
			return $this->scope;
		}

		public function get()
		{
			return "{$this->type} JOIN {$this->joinTable->table} ON {$this->column} = {$this->column2}";
		}

	}