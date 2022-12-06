<?php

	namespace Traineratwot\PDOExtended\statement;

	use PDO;
	use PDOStatement;
	use Traineratwot\PDOExtended\exceptions\DsnException;
	use Traineratwot\PDOExtended\Helpers;
	use Traineratwot\PDOExtended\PDOE;

	class PDOEPoolStatement extends PDOStatement
	{
		public PDOE  $connection;
		public array $pool = [];
		/**
		 * @var array<PDOEStatement|bool>
		 */
		public array $out;

		/**
		 * @param PDOE $connection
		 */
		private function __construct(PDOE $connection)
		{
			$this->connection = $connection;
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
			$this->out = [];
			foreach ($this->pool as $type => $pool) {
				$pool = array_chunk($pool, $limit);
				foreach ($pool as $queries) {
					$query = implode("\n", $queries);
					if ($type === 'query') {
						$this->out[] = $this->connection->query($query);
					} else {
						$this->out[] = (bool)$this->connection->exec($query);
					}
				}
			}
			return $this->out;
		}

		public function yieldAll(int $mode = PDO::FETCH_BOTH, ...$args)
		{
			foreach ($this->out as $statement) {
				if (!is_bool($statement)) {
					while ($row = $statement->fetch($mode, ...$args)) {
						yield $row;
					}
				} else {
					yield $statement;
				}
			}
		}

		public function getAll(int $mode = PDO::FETCH_BOTH, ...$args)
		{
			$rows = [];
			foreach ($this->out as $statement) {
				if (!is_bool($statement)) {
					while ($row = $statement->fetch($mode, ...$args)) {
						$rows[] = $row;
					}
				} else {
					$rows[] = $statement;
				}
			}
			return $rows;
		}
	}