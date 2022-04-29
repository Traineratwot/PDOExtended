<?php

	namespace Traineratwot\PDOExtended\abstracts;

	use Traineratwot\PDOExtended\PDOE;

	abstract class Driver
	{
		private $connection;

		/**
		 * @param PDOE $connection
		 */
		private function __construct(PDOE $connection)
		{
			$this->connection = $connection;
		}

		abstract public function getTablesList()
		: array;

	}