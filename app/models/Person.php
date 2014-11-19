<?php

use Phalcon\Db\RawValue;
use Phalcon\Mvc\Model;
//@todo ma hasMany a belongsTo nejaku vyhodu?

class Person extends Model {
	protected $id;
	protected $first_name;
	protected $last_name;
	protected $is_active;

	public function initialize()
	{
		$this->hasMany('id', 'VotingHistory', 'person_id',array('foreignKey' => true));
		$this->setIsActive( new RawValue( 'default' ) );
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
		$this->first_name = $first_name;
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
		$this->last_name = $last_name;
	}


}