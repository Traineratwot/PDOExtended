<?php

	namespace Traineratwot\PDOExtended\statement;

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
	}