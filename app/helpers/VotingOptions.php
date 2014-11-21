<?php
/**
 * Created by PhpStorm.
 * User: vetche
 * Date: 11/19/14
 * Time: 4:08 AM
 */

use Phalcon\Tag;

class VotingOptions extends Tag {

	private static $votingOptions = array(
		0 => "Did not vote",
		1 => "For",
		2 => "Against",
		3 => "Abstain"
	);

	static public function votingValue( $id ){
		if( !isset( $id ) || !is_int( $id ) ){
			throw new InvalidArgumentException();
		}

		return self::$votingOptions[ $id ];
	}

	static public function votingOptionsSelect( $settings ){
		if( is_null( $settings ) ){
			throw new InvalidArgumentException();
		}

		if( !is_array( $settings ) ){
			$params[ ] = $settings;
		} else {
			$params = $settings;
		}

		$selectId = isset( $params[ "id" ] ) ? ' id="' . $params[ "id" ] . '"' : ' id="' . $params[ 0 ] . '"';
		$selectName = isset( $params[ "id" ] ) ? ' name="' . $params[ "id" ] . '"' : ' name="' . $params[ 0 ] . '"';
		$selectClass = isset( $params[ "class" ] ) ? ' class="' . $params[ "class" ] . '"' : "";
		$emptyOption = isset( $params[ "useEmpty" ] ) ? "<option value=''>Choose...</option>" : "";

		$html = "<select " . $selectName . $selectId . $selectClass . ">" . $emptyOption;

		foreach( self::$votingOptions as $value => $option ){
			$html .= "<option value=" . $value . ">" . $option . "</option>";
		}

		$html .= "</select>";

		return $html;
	}
} 