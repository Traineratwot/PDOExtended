<?php

	namespace Traineratwot\PDOExtended\interfaces;

	interface DsnInterface
	{
		/**
		 * @return string
		 */
		public function get();
		/**
		 * @return string
		 */
		public function getDriver();
		/**
		 * @return string
		 */
		public function getPassword();
		/**
		 * @return string
		 */
		public function getUsername();
	}