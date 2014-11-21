<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:32 AM
 */
use Phalcon\Mvc\Model;

class VotingHistory extends Model {
	protected $id;
	protected $person_id;
	protected $point_id;
	protected $voted;

	/*
	 * voted values:
	 * 0 - did not vote
	 * 1 - for
	 * 2 - against
	 * 3 - abstain
	 */


	public function initialize(){
		$this->belongsTo( "person_id", "Person", "id", array( 'foreignKey' => true ) );
		$this->belongsTo( "point_id", "VotingPoint", "id", array( 'foreignKey' => true ) );
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
	public function getPersonId(){
		return $this->person_id;
	}

	/**
	 * @param mixed $person
	 */
	public function setPersonId( $person ){
		$this->person_id = $person;
	}

	/**
	 * @return mixed
	 */
	public function getPointId(){
		return $this->point_id;
	}

	/**
	 * @param mixed $point
	 */
	public function setPointId( $point ){
		$this->point_id = $point;
	}

	/**
	 * @return mixed
	 */
	public function getVoted(){
		return $this->voted;
	}

	/**
	 * @param mixed $voted
	 */
	public function setVoted( $voted ){
		$this->voted = $voted;
	}
}