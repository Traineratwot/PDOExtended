<?php

	namespace Traineratwot\PDOExtended\interfaces;


	use Traineratwot\PDOExtended\tableInfo\PDOEBdObject;
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

		/**
		 * @param string $table
		 * @return PDOEBdObject
		 * @noinspection ReturnTypeCanBeDeclaredInspection
		 */
		public function table(string $table);

		/**
		 * Escapes the table name
		 * @param string $table
		 * @return string
		 */
		public function escapeTable(string $table)
		: string;

		/**
		 * Escapes the column name
		 * @param string $column
		 * @return string
		 */
		public function escapeColumn(string $column)
		: string;
	}