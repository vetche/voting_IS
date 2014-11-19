<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:26 AM
 */

use Phalcon\Mvc\Controller;

class VotingPointController extends Controller {
	public function addAction(){
		if( count( $this->request->getPost()) > 0 ){
			$point = new VotingPoint();

			$point->setName( $this->request->getPost()[ 'name' ] );
			$point->setDescription( $this->request->getPost()[ 'description' ] );
			$point->setDate( $this->request->getPost()[ 'date' ] );

			//Store and check for errors
			if( $point->save() ){
				//echo "Thanks for registering!";
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $point->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "voting_point" );
			$this->view->disable();
			return;
		}
	}

	public function viewAction(){
		//@todo check na param
		//@todo spravit dispatcher file
		$point = VotingPoint::findFirst(
			array(
				"id = " . $this->dispatcher->getParams()[ 0 ]
			)
		);

		if( !$point ){
			//@todo 404 maybe
			echo 'Invalid id';
			$this->view->disable();
		}

		$this->view->setVar( "point", $point );
	}

	public function editAction(){

	}

	public function indexAction(){
		$points = VotingPoint::find(
			array(
				"order" => "date, name"
			)
		);

		$this->view->setVar( "points", $points );
	}
} 