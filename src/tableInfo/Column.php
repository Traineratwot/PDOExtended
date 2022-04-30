<?php

	namespace Traineratwot\PDOExtended\tableInfo;

	use Traineratwot\PDOExtended\abstracts\DataType;

	class Column
	{
		private string  $db_dataType;
		private string  $php_dataType;
		private string  $name;
		private ?string $links = NULL;

		private bool     $isPrimary    = FALSE;
		private bool     $isUnique     = FALSE;
		private bool     $canBeNull    = FALSE;
		private bool     $isSetDefault = FALSE;
		private string   $comment      = '';
		private DataType $validator;
		/**
		 * @var mixed|null
		 */
		private $default;

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
		: self
		{
			$this->db_dataType = $db_dataType;
			return $this;
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
		: self
		{
			$this->php_dataType = $php_dataType;
			return $this;
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
		: self
		{
			$this->name = $name;
			return $this;
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
		: self
		{
			$this->links = $links;
			return $this;
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
		 * @param $default
		 * @return Column
		 */
		public function setDefault($default)
		: self
		{
			$this->setIsSetDefault();
			$this->default = $default;
			return $this;
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
		: self
		{
			$this->isPrimary = $isPrimary;
			return $this;
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
		public function setIsUnique(?bool $isUnique = TRUE)
		: self
		{
			$this->isUnique = $isUnique;
			return $this;
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
		: self
		{
			$this->canBeNull = $canBeNull;
			return $this;
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
		public function setIsSetDefault(?bool $isSetDefault = TRUE)
		: self
		{
			$this->isSetDefault = $isSetDefault;
			return $this;
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
		: self
		{
			$this->comment = $comment;
			return $this;
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
		: self
		{
			$validator->setDefault($this->canBeNull, $this->default);
			$this->validator = $validator;
			$this->setPhpDataType($validator->phpName);
			return $this;
		}


		/**
		 * @return array
		 */
		public function toArray()
		: array
		{
			return [
				'db_dataType'  => $this->db_dataType,
				'php_dataType' => $this->php_dataType,
				'name'         => $this->name,
				'links'        => $this->links,
				'isPrimary'    => $this->isPrimary,
				'isUnique'     => $this->isUnique,
				'canBeNull'    => $this->canBeNull,
				'isSetDefault' => $this->isSetDefault,
				'comment'      => $this->comment,
				'validator'    => get_class($this->validator),
				'default'      => $this->default,
			];
		}

		public function __set($name, $value)
		{
			switch ($name) {
				case 'db_dataType':
					$this->setDbDataType($value);
					break;
				case 'php_dataType':
					$this->setPhpDataType($value);
					break;
				case 'name':
					$this->setName($value);
					break;
				case 'links':
					$this->setLinks($value);
					break;
				case 'isPrimary':
					$this->setIsPrimary($value);
					break;
				case 'isUnique':
					$this->setIsUnique($value);
					break;
				case 'canBeNull':
					$this->setCanBeNull($value);
					break;
				case 'isSetDefault':
					$this->setIsSetDefault($value);
					break;
				case 'comment':
					$this->setComment($value);
					break;
				case 'validator':
					$this->setValidator($value);
					break;
				case 'default':
					$this->setDefault($value);
					break;
			}
		}

		public static function __set_state($an_array)
		{
			$a = new self();
			foreach ($an_array as $key => $val) {
				$a->$key = $val;
			}
			return $a;
		}
	}