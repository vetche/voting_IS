<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:52 AM
 */
use Phalcon\Mvc\Model;

class VotingPointTag extends Model {
	protected $id;
	protected $point_id;
	protected $tag_id;

	public function initialize(){
		$this->belongsTo( 'point_id', 'VotingPoint', 'id', array( 'foreignKey' => true ) );
		$this->belongsTo( 'tag_id', 'Tags', 'id', array( 'foreignKey' => true ) );
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
	public function getTagId(){
		return $this->tag_id;
	}

	/**
	 * @param mixed $tag
	 */
	public function setTagId( $tag ){
		$this->tag_id = $tag;
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