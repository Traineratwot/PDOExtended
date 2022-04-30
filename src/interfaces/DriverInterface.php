<?php

	namespace Traineratwot\PDOExtended\interfaces;


	use Traineratwot\PDOExtended\tableInfo\Scheme;

	interface DriverInterface
	{

		public function getTablesList()
		: array;

		/**
		 * Checks if the table exists in the database. returns its correct name, case sensitive | FALSE
		 * @param string $table
		 * @return FALSE|string
		 */
		public function tableExists(string $table)
		: string;

		/**
		 * Checks if the table exists in the database. returns its correct name, case sensitive | FALSE
		 * @param string $table
		 * @return Scheme
		 */
		public function getScheme(string $table)
		: Scheme;

	}