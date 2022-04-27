<?php

	namespace Traineratwot\PDOExtended;

	use PDOStatement;

	class PDOEPoolStatement extends PDOStatement
	{
		public    $pool;
		protected $connection;

		public function __construct(PDOE $connection)
		{
			$this->connection = $connection;
		}

		public static function filter(&$v, $k)
		{
			if (!is_numeric($v) && $v !== "NULL") {
				$v = "\'" . $v . "\'";
			}
		}

		/**
		 * Add prepared query to pool queue
		 * @param $input_parameters
		 * @return $this|bool
		 */
		public function execute($input_parameters = NULL)
		{
			$this->connection->queryCountIncrement();
			$this->pool[] = $this->interpolateQuery($this->queryString, $input_parameters);
			return $this;
		}

		/**
		 * @param $query
		 * @param $params
		 * @return string
		 */
		public function interpolateQuery($query, $params)
		{
			$keys         = [];
			$values       = $params;
			$values_limit = [];

			$words_repeated = array_count_values(str_word_count($query, 1, ':_'));

			# build a regular expression for each parameter
			foreach ($params as $key => $value) {
				if (is_string($key)) {
					$keys[]             = '/:' . $key . '/';
					$values_limit[$key] = (isset($words_repeated[':' . $key]) ? (int)$words_repeated[':' . $key] : 1);
				} else {
					$keys[]       = '/[?]/';
					$values_limit = [];
				}
				if (is_string($value)) {
					$values[$key] = $value;
				}

				if (is_array($value)) {
					$values[$key] = json_encode($value);
				}

				if (is_null($value)) {
					$values[$key] = 'NULL';
				}
			}
			if (is_array($values)) {
				foreach ($values as $key => $val) {
					if (isset($values_limit[$key])) {
						$query = preg_replace(['/:' . $key . '/'], [$val], $query, $values_limit[$key], $count);
					} else {
						$query = preg_replace(['/:' . $key . '/'], [$val], $query, 1, $count);
					}
				}
				unset($key, $val);
			} else {
				$query = preg_replace($keys, $values, $query, 1, $count);
			}

			unset($keys, $values, $values_limit, $words_repeated);

			return trim(trim($query), ';');
		}

		/**
		 * execute queries pool
		 *
		 * sqlite cannot execute more than one query at a time
		 * @param int $limit //count of query from chunk; default 10
		 * @return Array<PDOEStatement>
		 */
		public function run($limit = 10)
		{
			//sqlite cannot execute more than one query at a time
			if ($this->connection->dsn['driver'] === "sqlite") {
				$limit = 1;
			}
			$pools   = array_chunk($this->pool, $limit);
			$queries = [];
			foreach ($pools as $pool) {
				$query     = implode(";", $pool) . ';';
				$queries[] = $this->connection->query($query);
			}
			return $queries;
		}
	}