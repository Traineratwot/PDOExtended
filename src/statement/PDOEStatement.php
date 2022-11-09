<?php

	namespace Traineratwot\PDOExtended\statement;

	use PDO;
	use PDOStatement;
	use Traineratwot\PDOExtended\PDOE;

	class PDOEStatement extends PDOStatement
	{
		private PDOE $connection;

		/**
		 * @param PDOE $connection
		 */
		private function __construct(PDOE $connection)
		{
			$this->connection = $connection;
		}

		/**
		 * @inheritDoc
		 * @param array $params
		 * @return bool
		 */
		public function execute($params = [])
		: bool
		{
			$this->connection->queryCountIncrement();
			$tStart = microtime(TRUE);
			$this->connection->log($this->queryString);
			$return = parent::execute($params);
			$this->connection->queryTimeIncrement(microtime(TRUE) - $tStart);
			return $return;
		}
		
		public function yieldAll(int $mode = PDO::FETCH_BOTH, ...$args)
		{
			$rows = [];
			while ($row = $this->fetch($mode, ...$args)) {
				$rows[] = $row;
			}
			return $rows;
		}
	}