<?php

	namespace Traineratwot\PDOExtended\statement;

	use PDOStatement;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	class PDOEPoolStatement extends PDOStatement
	{
		public    $pool;
		protected $connection;

		private function __construct(PDOE $connection)
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
		 * @param null $input_parameters
		 * @param bool $return if true use 'query' else 'exec'
		 * @return $this|bool
		 */
		public function execute($input_parameters = NULL, $key = NULL, $return = TRUE)
		{
			$type = $return ? 'query' : 'exec';
			$this->connection->queryCountIncrement();
			$this->pool[$type][] = $this->interpolateQuery($this->queryString, $input_parameters);
			return $this;
		}

		/**
		 * @param $query
		 * @param $params
		 * @return string
		 */
		public function interpolateQuery($query, $params)
		{
			return Helpers::prepare($query, $params);
		}

		/**
		 * execute queries pool
		 *
		 * sqlite cannot execute more than one query at a time
		 * @param int $limit //count of query from chunk; default 10
		 * @return Array<PDOEStatement|bool>
		 * @throws DsnException
		 */
		public function run($limit = 10)
		{
			//sqlite cannot execute more than one query at a time
			if ($this->connection->dsn->getDriver() === PDOE::DRIVER_SQLite) {
				$limit = 1;
			}
			$pools   = array_chunk($this->pool, $limit);
			$queries = [];
			foreach ($pools as $type => $pool) {
				foreach ($pool as $query) {
					if ($type === 'query') {
						$queries[] = $this->connection->query($query);
					} else {
						$queries[] = (bool)$this->connection->exec($query);
					}
				}
			}
			return $queries;
		}
	}