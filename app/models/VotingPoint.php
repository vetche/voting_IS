<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:26 AM
 */
use Phalcon\Mvc\Model;

class VotingPoint extends Model {
	protected $id;
	protected $name;
	protected $description;
	protected $date;

	public function initialize()
	{
		$this->hasMany('id', 'VotingHistory', 'point_id', array('foreignKey' => true));
		$this->setDescription( '' );
	}

	public function columnMap() {
		return array(
			'id' => 'id',
			'name' => 'name',
			'description' => 'description',
			'date' => 'date'
		);
	}

	/**
	 * @return mixed
	 */
	public function getDate(){
		return $this->date;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate( $date ){
		$this->date = $date;
	}

	/**
	 * @return mixed
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription( $description ){
		$this->description = $description;
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
		$this->name = $name;
	}


} 