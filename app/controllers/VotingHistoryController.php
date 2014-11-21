<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/18/14
 * Time: 1:13 PM
 */
use Phalcon\Mvc\Controller;

class VotingHistoryController extends Controller {
	public function addAction(){
		if( count( $this->request->getPost()) > 0 ){
			$point = new VotingHistory();

			$point->setPersonId( $this->request->getPost()[ 'person' ] );
			$point->setPointId( $this->request->getPost()[ 'point' ] );
			$point->setVoted( $this->request->getPost()[ 'voted' ] );

			//Store and check for errors
			if( $point->save() ){
				echo "Thanks for registering!";
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $point->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "voting_history" );
			$this->view->disable();
			return;
		}
	}

	public function viewAction(){
		//@todo check na param
		//@todo spravit dispatcher file

		$point = VotingHistory::findFirst(
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

	public function deleteAction(){

	}

	public function indexAction(){
		$history = VotingHistory::find(
			array(
				"order" => "point_id DESC, person_id"
			)
		);

		$this->view->setVar( "history", $history );
	}
} 