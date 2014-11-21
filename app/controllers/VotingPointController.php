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
		$pointId = $this->dispatcher->getParams()[ 0 ];

		$history = VotingHistory::find(
			array(
				"point_id = " . $pointId
			)
		);

		if( count( $history ) == 0 ){
			$point = VotingPoint::findFirst(
				array(
					"id = " . $pointId
				)
			);

			if( !$point ){
				//@todo 404 maybe
				echo 'Invalid voting point';
				$this->view->disable();
			} else {
				$this->view->setVar( "point", $point );
			}
		} else {
			$this->view->setVar( "point", $history[ 0 ]->votingPoint );
			$this->view->setVar( "history", $history );
		}
	}

	public function editAction(){
		//@todo check na param + dispatcher
		$postedParams = $this->request->getPost();

		$point = VotingPoint::findFirst(
			array(
				"id = " . $this->dispatcher->getParams()[ 0 ]
			)
		);

		if( count( $postedParams ) > 0 ){
			$point->setName( $postedParams[ "name" ] );
			$point->setDescription( $postedParams[ "description" ] );
			$point->setDate( $postedParams[ "date" ] );

			//Store and check for errors
			if( $point->save() ){
				//daco
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

		$this->view->setVar( "point", $point );
	}

	public function indexAction(){
		$points = VotingPoint::find(
			array(
				"order" => "date DESC, name"
			)
		);

		$this->view->setVar( "points", $points );
	}
} 