<?php

	namespace Traineratwot\PDOExtended\statement;

	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	class PDOEPoolStatement extends PDOEStatement
	{
		public array   $pool = [];
		protected PDOE $connection;

		/**
		 * Add prepared query to pool queue
		 * @param null $input_parameters
		 * @param bool $return if true use 'query' else 'exec'
		 * @return $this|bool
		 */
		public function execute($params = [])
		: bool
		{
			$this->add($params, FALSE);
			return TRUE;
		}

		public function add(array $params, $return = FALSE)
		{
			$type                = $return ? 'query' : 'exec';
			$this->pool[$type][] = Helpers::prepare($this->queryString, $params);
			return $this;
		}

		/**
		 * execute queries pool
		 *
		 * sqlite cannot execute more than one query at a time
		 * @param int $limit //count of query from chunk; default 10
		 * @return Array<PDOEStatement|bool>
		 * @throws DsnException
		 */
		public function run(int $limit = 10)
		: array
		{
			//sqlite cannot execute more than one query at a time
//			if ($this->connection->dsn->getDriver() === PDOE::DRIVER_SQLite) {
//				$limit = 1;
//			}
			$out = [];
			foreach ($this->pool as $type => $pool) {
				$pool = array_chunk($pool, $limit);
				foreach ($pool as $queries) {
					$query = implode("\n", $queries);
					if ($type === 'query') {
						$out[] = $this->connection->query($query);
					} else {
						$out[] = (bool)$this->connection->exec($query);
					}
				}
			}
			return $out;
		}
	}