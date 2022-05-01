<?php

	namespace Traineratwot\PDOExtended\abstracts\builders;

	use Traineratwot\PDOExtended\abstracts\builder;
	use Traineratwot\PDOExtended\abstracts\Driver;
	use Traineratwot\PDOExtended\Helpers;

	abstract class Where
	{
		private array   $_where = [];
		private array   $values = [];
		private int     $i      = 0;
		private Driver  $driver;
		private builder $scope;

		public function __construct(Builder $scope, Driver $driver, $callback = NULL)
		{
			$this->driver = $driver;
			$this->scope  = $scope;

			if (is_callable($callback)) {
				$callback($this);
			}
		}

		/**
		 * @return builder
		 */
		public function end()
		{
			return $this->scope;
		}

		/**
		 * @param $values
		 * @return string
		 */
		private function setValue($values)
		{
			$this->i++;
			$key                = ':v' . $this->i;
			$this->values[$key] = $values;
			return $key;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function eq(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->eq($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function notEq(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->notEq($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function greater(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->greater($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function greaterEq(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->greaterEq($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function less(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->less($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param        $value
		 * @return $this
		 */
		public function lessEq(string $column, $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->lessEq($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param array  $value
		 * @return $this
		 */
		public function in(string $column, array $value)
		{

			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->in($column, $key)->get();
			return $this;
		}

		/**
		 * @param string $column
		 * @param array  $value
		 * @return $this
		 */
		public function notIn(string $column, array $value)
		{
			$key            = $this->setValue($value);
			$this->_where[] = (new WherePart($this->driver, $this->scope))->notIn($column, $key)->get();
			return $this;
		}

		/**
		 * @return $this
		 */
		public function and()
		{
			$this->_where[] = $this->driver->and;
			return $this;
		}

		/**
		 * @return $this
		 */
		public function or()
		{
			$this->_where[] = $this->driver->or;
			return $this;
		}

		/**
		 * @return array
		 */
		public function getValues()
		{
			return $this->values;
		}

		/**
		 * @return string
		 */
		public function get()
		: string
		{
			return implode(' ', $this->_where);
		}

		/**
		 * @return string
		 */
		public function toSql()
		: string
		{
			return Helpers::prepare($this->get(), $this->getValues(), $this->driver->connection, '');
		}
	}