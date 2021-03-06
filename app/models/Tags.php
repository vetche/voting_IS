<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:31 AM
 */
use Phalcon\Mvc\Model;

class Tags extends Model {
	protected $id;
	protected $name;

	public function initialize(){
		$this->setSource( "tag" );
		$this->hasMany( 'id', 'VotingPointTag', 'tag_id', array( 'foreignKey' => true ) );
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
	public function getName(){
		return $this->name;
	}

	/**
	 * @param mixed $name
	 */
	public function setName( $name ){
		$this->name = strtolower( $name );
	}


} 