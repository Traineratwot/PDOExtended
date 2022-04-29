<?php

	namespace Traineratwot\PDOExtended\interfaces;

	interface DsnInterface
	{
		/**
		 * @return string
		 */
		public function get()
		: string;

		/**
		 * @return string
		 */
		public function getDriver()
		: string;

		/**
		 * @return string
		 */
		public function getPassword()
		: string;

		/**
		 * @return string
		 */
		public function getUsername()
		: string;
	}