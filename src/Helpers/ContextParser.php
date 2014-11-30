<?php

namespace WCM\AstroFields\Core\Helpers;

/**
 * Class ContextParser
 * @package WCM\AstroFields\Core\Helpers
 * Based upon the answer by @author deceze on StackOverflow
 * @link http://stackoverflow.com/a/6313849/376483
 * @license CC-BY-SA 3.0
 */
class ContextParser implements ParserInterface
{
	/** @type Array */
	private $input;

	/** @type string */
	private $context;

	/**
	 * @param array  $input
	 * @param string $context
	 */
	public function setup( Array $input, $context )
	{
		# $input = array_map( 'strtolower', $input );
		# $input = array_change_key_case( $input, CASE_LOWER );
		$this->input   = array_filter( $input );
		$this->context = $context;
	}

	/**
	 * @return array
	 */
	public function getResult()
	{
		return array_values( $this->map(
			$this->cartesian( $this->input ),
			$this->context
		) );
	}

	/**
	 * @param array  $product
	 * @param string $context
	 * @return array
	 */
	protected function map( Array $product, $context )
	{
		$results = array();
		foreach ( $product as $part )
		{
			$temp = $context;
			foreach ( $part as $key => $value )
				$temp = str_replace( $key, $value, $temp );

			$results[] = $temp;
		}

		return array_unique( $results );
	}

	/**
	 * @param array $array
	 * @return array
	 */
	protected function cartesian( Array $array )
	{
		$keys    = array_keys( $array );
		$product = array_shift( $array );
		$product = array_reduce(
			$array,
			array( $this, 'zip' ),
			$product
		);

		return array_map( function( $n ) use ( $keys )
		{
			return array_combine(
				$keys,
				(array) $n
			);
		}, $product );
	}

	/**
	 * @param array $array1
	 * @param array $array2
	 * @return mixed
	 */
	protected function zip( Array $array1, Array $array2 )
	{
		$parser = $this;
		return array_reduce( $array1, function( $value, $key ) use ( $array2, $parser )
		{
			return array_merge(
				$value,
				$parser->inject( $key, $array2 )
			);
		}, array() );
	}

	/**
	 * @param string $element
	 * @param array  $array
	 * @return array
	 */
	protected function inject( $element, Array $array )
	{
		return array_map( function( $n ) use ( $element )
		{
			return array_merge(
				(array) $element,
				(array) $n
			);
		}, $array );
	}
}