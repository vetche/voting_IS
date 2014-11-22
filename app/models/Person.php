<?php

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;

//@todo poriesit kaskadovanie v db

class Person extends Model {
	protected $id;
	protected $first_name;
	protected $last_name;
	protected $is_active;

	public function initialize(){
		$this->hasMany( 'id', 'VotingHistory', 'person_id', array( 'foreignKey' => true ) );
		$this->setIsActive( new RawValue( 'default' ) );
	}

	private function validateField( $fieldValue, $displayName ){
		if( empty( $fieldValue ) ){
			throw new InvalidArgumentException( $displayName . " cannot be empty" );
		}

		if( preg_match( "/[^\p{L}\s\.\-]/u", $fieldValue ) ){
			throw new InvalidArgumentException( "Invalid characters in " . $displayName );
		}

		return true;
	}

	/**
	 * @return mixed
	 */
	public function getFirstName(){
		return $this->first_name;
	}

	/**
	 * @param mixed $first_name
	 */
	public function setFirstName( $first_name ){
		$first_name = trim( $first_name );
		$this->validateField( $first_name, "First name" );

		$this->first_name = ucfirst( $first_name );
	}

	/**
	 * @return mixed
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId( $id ){
		if( !is_int( $id ) ){
			throw new InvalidArgumentException( "Id must be an integer." );
		}
		$this->id = $id;
	}

	/**
	 * @return mixed
	 */
	public function getIsActive(){
		return $this->is_active;
	}

	/**
	 * @param mixed $is_active
	 */
	public function setIsActive( $is_active ){
		if( !is_bool( $is_active ) && $is_active != 'default' ){
			throw new InvalidArgumentException( "Is_active must be a boolean" );
		}
		$this->is_active = $is_active;
	}

	/**
	 * @return mixed
	 */
	public function getLastName(){
		return $this->last_name;
	}

	/**
	 * @param mixed $last_name
	 */
	public function setLastName( $last_name ){
		$last_name = trim( $last_name );
		$this->validateField( $last_name, "First name" );
		$this->last_name = ucfirst( $last_name );
	}

	public function getName( $lexicalOrder = true ){
		return $lexicalOrder ? ( $this->getLastName() . " " . $this->getFirstName() ) : ( $this->getFirstName() . " " . $this->getLastName() );
	}
}