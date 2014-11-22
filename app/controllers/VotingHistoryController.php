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
		//@todo check na param + dispatcher
		$postedParams = $this->request->getPost();

		$history = VotingHistory::findFirst(
			array(
				"id = " . $this->dispatcher->getParams()[ 0 ]
			)
		);

		if( !$history ){
			//@todo 404 maybe
			echo 'Invalid id';
			$this->view->disable();
		}

		if( count( $postedParams ) > 0 ){
			$history->setVoted( $postedParams[ "voted" ] );

			//Store and check for errors
			if( $history->save() ){
				//daco
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $history->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "voting_history" );
			$this->view->disable();
			return;
		}

		$this->view->setVar( "history", $history );
	}

	public function deleteAction(){
		//@todo pridat checky + js confirm
		$history = VotingHistory::findFirst( $this->dispatcher->getParams()[ 0 ] );
		if( $history != false ){
			if( $history->delete() == false ){
				echo "Sorry, we can't delete the entry right now: \n";
				foreach( $history->getMessages() as $message ){
					echo $message, "\n";
				}
			} else {
				echo "The entry was deleted successfully!";
			}
		}
	}

	public function indexAction(){
		$history = VotingHistory::find(
			array(
				"order" => "point_id DESC, person_id"
			)
		);

		$tagNames = array();
		$tags = array();
		foreach( Tags::find( array( "order" => "name" ) ) as $tag ){
			$tagName = $tag->getName();

			$associatedPoints = $tag->votingPointTag;
			if( count( $associatedPoints ) != 0 ){
				foreach( $associatedPoints as $vp ){
					$tags[ $vp->getPointId() ][ ] = $tagName;
				}

			}

			$tagNames[ ] = $tagName;
		}

		$this->view->setVar( "tagNames", $tagNames );
		$this->view->setVar( "tags", $tags );
		$this->view->setVar( "history", $history );
	}
} 