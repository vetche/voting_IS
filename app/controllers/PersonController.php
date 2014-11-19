<?php

/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/16/14
 * Time: 4:51 PM
 */
use Phalcon\Mvc\Controller;
//@todo spravit nejak inteligente concat na first a last name

class PersonController extends Controller {
	public function addAction( ){
		if( count( $this->request->getPost()) > 0 ){
			$person = new Person();

			$person->setFirstName( $this->request->getPost()[ 'name' ] );
			$person->setLastName( $this->request->getPost()[ 'surname' ] );

			//Store and check for errors
			if( $person->save() ){
				//daco
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $person->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "person" );
			$this->view->disable();
			return;
		}
	}

	public function editAction(){
		$postedParams = $this->request->getPost();

		if( count( $postedParams ) > 0 ){
			$person = Person::findFirst(
				array(
					"id = " . $this->dispatcher->getParams()[ 0 ]
				));


			$person->setFirstName( $postedParams[ "name" ] );
			$person->setLastName( $postedParams[ "surname" ] );
			$person->setIsActive( isset( $postedParams[ "is_active" ] ) );

			//Store and check for errors
			if( $person->save() ){
				//daco
			} else {
				echo "Sorry, the following problems were generated: ";
				foreach( $person->getMessages() as $message ){
					echo $message->getMessage(), "<br/>";
				}
			}

			$this->response->redirect( "person" );
			$this->view->disable();
			return;
		}

		$user = Person::findFirst(
			array(
				"id = " . $this->dispatcher->getParams()[ 0 ]
			)
		);

		if( ! $user ){
			//@todo 404 maybe
			echo 'Invalid id';
			$this->view->disable();
		}

		$this->view->setVar( "user", $user );
	}

	public function viewAction(){
		//@todo check na param
		//@todo spravit dispatcher file

		$user = Person::findFirst(
			array(
				"id = " . $this->dispatcher->getParams()[ 0 ]
			)
		);

		if( ! $user ){
			//@todo 404 maybe
			echo 'Invalid id';
			$this->view->disable();
		}

		$this->view->setVar( "user", $user );

	}

	public function indexAction(){
		$users = Person::find(
			array(
				"is_active = true",
				"order" => "last_name, first_name"
			)
		);

		$this->view->setVar( "users", $users );
	}
} 