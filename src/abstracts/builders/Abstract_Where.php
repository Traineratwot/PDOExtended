<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\exceptions\SqlBuildException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\tableInfo\Scheme;

	abstract class Abstract_Where
	{
		public array   $_where     = [];
		public array   $values     = [];
		public int     $i          = 0;
		public Driver  $driver;
		public Builder $scope;
		private Scheme $scheme;
		private array  $validators = [];

		/**
		 * @throws SqlBuildException
		 */
		public function __construct(Builder $scope, $callback = NULL)
		{
			$this->scope  = $scope;
			$this->driver = $scope->driver;
			$this->scheme = $scope->scheme;

			if (!is_null($callback)) {
				if (is_callable($callback)) {
					$callback($this);
				} else {
					$column = $this->scope->scheme->getPrimaryKey();
					if (!$column) {
						throw new SqlBuildException("Table '{$this->scope->scheme->name}'");
					}
					$this->eq($column->getName(), $callback);
				}
			}
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function eq(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->eq($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param $values
		 * @return string
		 */
		private function setValue($values)
		{
			$this->i++;
			$key                = ":PDOE_{$this->i}_where";
			$this->values[$key] = $values;
			return $key;
		}

		/**
		 * @param array $validators
		 * @return string
		 */
		public function get(array &$validators = [])
		: string
		{
			$validators = array_merge($validators, $this->validators);
			return implode(' ', $this->_where);
		}

		/**
		 * @return Builder
		 */
		public function end()
		{
			return $this->scope;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function notEq(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->notEq($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function greater(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->greater($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function greaterEq(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->greaterEq($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function less(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->less($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function lessEq(string $column, $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->lessEq($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param array  $value
		 * @return $this
		 */
		public function in(string $column, array $value)
		{

			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->in($column, $key)->get();
//			$val                    = $this->scheme->getColumn($column);
//			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @param string $column
		 * @param array  $value
		 * @return $this
		 */
		public function notIn(string $column, array $value)
		{
			$key                    = $this->setValue($value);
			$cls                    = $this->driver->tools['WherePart'];
			$this->_where[]         = (new $cls($this->driver, $this))->notIn($column, $key)->get();
			$val                    = $this->scheme->getColumn($column);
			$this->validators[trim($key, ':')] = $val->validator;
			return $this;
		}

		/**
		 * @return $this
		 */
		public function and(?callable $callback = NULL)
		{
			if (!empty($this->_where)) {
				$this->_where[] = $this->driver->and;
			}
			if ($callback) {
				$this->block();
				$callback($this);
				$this->endBlock();
			}
			return $this;
		}

		/**
		 * start rule block
		 * @return $this
		 */
		public function block()
		{
			$this->_where[] = $this->driver->block;
			return $this;
		}

		/**
		 * End rule block
		 * @return $this
		 */
		public function endBlock()
		{
			$this->_where[] = $this->driver->endBlock;
			return $this;
		}

		/**
		 * add or condition
		 * @return $this
		 */
		public function or(?callable $callback = NULL)
		{
			if (!empty($this->_where)) {
				$this->_where[] = $this->driver->or;
			}
			if ($callback) {
				$this->block();
				$callback($this);
				$this->endBlock();
			}
			return $this;
		}

		/**
		 * ##NOT SAFE
		 * add custom text to where
		 * @param string $string
		 * @return $this
		 */
		public function add(string $string)
		{
			$this->_where[] = $string;
			return $this;
		}

		/**
		 * @return string
		 */
		public function toSql()
		: string
		{
			return Helpers::prepare($this->get(), $this->getValues(), function ($val, $key) {
				if ($this->validators[trim($key, ':')]) {
					return $this->validators[trim($key, ':')]->escape($this->driver->connection, $val);
				}
				return Helpers::getValue($this->driver->connection, $val);
			},                      '');
		}

		/**
		 * @return array
		 */
		public function getValues()
		: array
		{
			return $this->values;
		}
	}