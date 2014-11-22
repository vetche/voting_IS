<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/21/14
 * Time: 3:04 PM
 */
use Phalcon\Mvc\Controller;

class TagsController extends Controller {
	public function indexAction(){
		$tags = Tags::find(
			array( "order" => "name" )
		);

		$this->view->setVar( "tags", $tags );
	}

	public function viewAction(){
		$tagId = $this->dispatcher->getParams()[ 0 ];

		$points = VotingPointTag::find(
			array(
				"tag_id = " . $tagId
			)
		);

		if( count( $points ) == 0 ){
			$tag = Tags::findFirst( $tagId );

			if( !$tag ){
				echo 'No such tag';
				return;
			}

			$this->view->setVar( "tag", $tag );
			return;
		}

		$this->view->setVar( "tag", $points[ 0 ]->tags );
		$this->view->setVar( "points", $points );
	}

	public function editAction(){

	}

	public function addAction(){
		if( count( $this->request->getPost() ) > 0 ){
			$tag = new Tags();

			$tag->setName( $this->request->getPost()[ 'name' ] );

			//Store and check for errors
			if( $tag->save() ){
				//daco
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $tag->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "tags" );
			$this->view->disable();
			return;
		}
	}

	public function deleteAction(){

	}
} 