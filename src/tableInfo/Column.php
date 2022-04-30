<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class Column
	{
		private string   $db_dataType;
		private string   $php_dataType;
		private string   $name;
		private string   $links;
		private string   $default;
		private bool     $isPrimary;
		private bool     $isUnique;
		private bool     $canBeNull;
		private bool     $isSetDefault;
		private string   $comment = '';
		private DataType $validator;

		/**
		 * @return string
		 */
		public function getDbDataType()
		: string
		{
			return $this->db_dataType;
		}

		/**
		 * @param string $db_dataType
		 */
		public function setDbDataType(string $db_dataType)
		: void
		{
			$this->db_dataType = $db_dataType;
		}

		/**
		 * @return string
		 */
		public function getPhpDataType()
		: string
		{
			return $this->php_dataType;
		}

		/**
		 * @param string $php_dataType
		 */
		public function setPhpDataType(string $php_dataType)
		: void
		{
			$this->php_dataType = $php_dataType;
		}

		/**
		 * @return string
		 */
		public function getName()
		: string
		{
			return $this->name;
		}

		/**
		 * @param string $name
		 */
		public function setName(string $name)
		: void
		{
			$this->name = $name;
		}

		/**
		 * @return string
		 */
		public function getLinks()
		: string
		{
			return $this->links;
		}

		/**
		 * @param string $links
		 */
		public function setLinks(string $links)
		: void
		{
			$this->links = $links;
		}

		/**
		 * @return string
		 */
		public function getDefault()
		: string
		{
			return $this->default;
		}

		/**
		 * @param string $default
		 */
		public function setDefault(string $default)
		: void
		{
			$this->default = $default;
		}

		/**
		 * @return bool
		 */
		public function isPrimary()
		: bool
		{
			return $this->isPrimary;
		}

		/**
		 * @param bool $isPrimary
		 */
		public function setIsPrimary(bool $isPrimary)
		: void
		{
			$this->isPrimary = $isPrimary;
		}

		/**
		 * @return bool
		 */
		public function isUnique()
		: bool
		{
			return $this->isUnique;
		}

		/**
		 * @param bool $isUnique
		 */
		public function setIsUnique(bool $isUnique)
		: void
		{
			$this->isUnique = $isUnique;
		}

		/**
		 * @return bool
		 */
		public function isCanBeNull()
		: bool
		{
			return $this->canBeNull;
		}

		/**
		 * @param bool $canBeNull
		 */
		public function setCanBeNull(bool $canBeNull)
		: void
		{
			$this->canBeNull = $canBeNull;
		}

		/**
		 * @return bool
		 */
		public function isSetDefault()
		: bool
		{
			return $this->isSetDefault;
		}

		/**
		 * @param bool $isSetDefault
		 */
		public function setIsSetDefault(bool $isSetDefault)
		: void
		{
			$this->isSetDefault = $isSetDefault;
		}

		/**
		 * @return string
		 */
		public function getComment()
		: string
		{
			return $this->comment;
		}

		/**
		 * @param string $comment
		 */
		public function setComment(string $comment)
		: void
		{
			$this->comment = $comment;
		}

		/**
		 * @return DataType
		 */
		public function getValidator()
		: DataType
		{
			return $this->validator;
		}

		/**
		 * @param DataType $validator
		 */
		public function setValidator(DataType $validator)
		: void
		{
			$this->validator = $validator;
		}

	}