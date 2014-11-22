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
		$postedParams = $this->request->getPost();

		if( count( $postedParams ) > 0 ){
			$point = new VotingPoint();

			$point->setName( $postedParams[ 'name' ] );
			$point->setDescription( $postedParams[ 'description' ] );
			$point->setDate( $postedParams[ 'date' ] );

			//Store and check for errors
			if( $point->save() ){
				if( isset( $postedParams[ "tags" ] ) ){
					foreach( $postedParams[ "tags" ] as $tag ){
						$vp_tag = new VotingPointTag();
						$vp_tag->setPointId( $point->getId() );
						$vp_tag->setTagId( $tag );

						$vp_tag->save();
					}
				}
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

		$tags = VotingPointTag::find(
			array(
				"point_id = " . $pointId
			) );

		$tagsArray = array();
		foreach( $tags as $tag ){
			$tagsArray[ ] = $tag->tags->getName();
		}

		$this->view->setVar( "tags", $tagsArray );

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

			return;
		}

		$this->view->setVar( "point", $history[ 0 ]->votingPoint );
		$this->view->setVar( "history", $history );
	}

	public function editAction(){
		//@todo check na param + dispatcher
		$postedParams = $this->request->getPost();
		$pointId = $this->dispatcher->getParams()[ 0 ];

		$point = VotingPoint::findFirst(
			array(
				"id = " . $pointId
			)
		);

		$tags = VotingPointTag::find( "point_id=" . $pointId );

		if( count( $postedParams ) > 0 ){
			$point->setName( $postedParams[ "name" ] );
			$point->setDescription( $postedParams[ "description" ] );
			$point->setDate( $postedParams[ "date" ] );

			if( !isset( $postedParams[ "tags" ] ) ){
				foreach( $tags as $vp_tag ){
					$vp_tag->delete();
				}
			} else {
				$tagsIds = array();
				foreach( $tags as $vp_tag ){
					$tagsIds[ ] = $vp_tag->getTagId();
				}

				$toDelete = array_diff( $tagsIds, $postedParams[ "tags" ] );
				$toInsert = array_diff( $postedParams[ "tags" ], $tagsIds );

				foreach( $tags as $vp_tag ){
					if( in_array( $vp_tag->getTagId(), $toDelete ) ){
						$vp_tag->delete();
					}
				}

				foreach( $toInsert as $tagId ){
					$vp_tag = new VotingPointTag();
					$vp_tag->setPointId( $point->getId() );
					$vp_tag->setTagId( $tagId );

					$vp_tag->save();
				}
			}

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

		$associatedTags = array();
		foreach( $tags as $tag ){
			$associatedTags[ ] = $tag->getTagId();
		}

		$this->view->setVar( "tags", $associatedTags );
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