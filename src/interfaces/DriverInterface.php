<?php

	namespace Traineratwot\PDOExtended\interfaces;


	interface DriverInterface
	{

		public function getTablesList()
		: array;

		/**
		 * Checks if the table exists in the database. returns its correct name, case sensitive | FALSE
		 * @param string $table
		 * @return FALSE|string
		 */
		public function tableExists(string $table);

	}