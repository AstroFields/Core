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
	 * Temporary storage for nested `array_reduce()` in `zip()`
	 * @type Array
	 */
	private $tmp;

	/**
	 * Attach input and context to the properties
	 * Filters out/Removes empty values from the input
	 * @codeCoverageIgnore
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
	 * Return the parsed context array
	 * Array keys (numerical) get sorted
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
	 * @codeCoverageIgnore
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
	 * @codeCoverageIgnore
	 * @param array $input
	 * @return array
	 */
	protected function cartesian( Array $input )
	{
		$keys    = array_keys( $input );
		$product = array_shift( $input );
		$product = array_reduce(
			$input,
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
	 * @codeCoverageIgnore
	 * @param array $input
	 * @param array $product
	 * @return mixed
	 */
	protected function zip( Array $input, Array $product )
	{
		// PHP 5.3 fix
		$parser = $this;

		return array_reduce( $input, function( $carry, $item ) use ( $product, $parser )
		{
			return array_merge(
				$carry,
				$parser->inject( $item, $product )
			);
		}, array() );
	}

	/**
	 * @codeCoverageIgnore
	 * @param $carry
	 * @param $item
	 * @return array
	 */
	protected function reduce( $carry, $item )
	{
		return array_merge(
			$carry,
			$this->inject( $item, $this->tmp )
		);
	}

	/**
	 * @codeCoverageIgnore
	 * @param string $element
	 * @param array  $array
	 * @return array
	 */
	public function inject( $element, Array $array )
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