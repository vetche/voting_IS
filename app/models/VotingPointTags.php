<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:52 AM
 */
use Phalcon\Mvc\Model;

class VotingPointTags extends Model {
	protected $id;
	protected $point_id;
	protected $tag;

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
	public function getTag(){
		return $this->tag;
	}

	/**
	 * @param mixed $tag
	 */
	public function setTag( $tag ){
		$this->tag = $tag;
	}

	/**
	 * @return mixed
	 */
	public function getPointId(){
		return $this->point_id;
	}

	/**
	 * @param mixed $voting_point
	 */
	public function setPointId( $voting_point ){
		$this->point_id = $voting_point;
	}


} 