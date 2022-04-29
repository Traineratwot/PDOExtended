<?php

	namespace Traineratwot\PDOExtended\abstracts;


	use Traineratwot\PDOExtended\interfaces\DriverInterface;
	use Traineratwot\PDOExtended\PDOE;

	abstract class Driver implements DriverInterface
	{
		protected $connection;

		/**
		 * @param PDOE $connection
		 */
		public function __construct(PDOE $connection)
		{
			$this->connection = $connection;
		}

		abstract public function getTablesList()
		: array;


		/**
		 * @inheritDoc
		 * @return false|string
		 */
		public function tableExists($table)
		{
			$list = $this->getTablesList();
			$find = FALSE;
			foreach ($list as $t) {
				if (mb_strtolower($t) === mb_strtolower($table)) {
					$find = TRUE;
					break;
				}
			}
			return $find ? $t : FALSE;
		}
	}