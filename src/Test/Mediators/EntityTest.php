<?php

namespace WCM\AstroFields\Core\Test\Mediators;

use WCM\AstroFields\Core\Mediators\Entity;

class EntityTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider getTestContext()
	 */
	public function testParseContext( $context, Array $info = array() )
	{
		$entity = new Entity( 'test', array(
			'post',
			'page',
		) );
		#$entity->parseContext( $context, $info );
	}

	public function getTestContext()
	{
		return array(
			array( '{key}_bar', array( 'key' => 'foo', ) ),
			array( 'foo_{type}', array( 'type' => 'bar', ) ),
			array( '{key}_{type}', array( 'key' => 'foo', 'type' => 'bar', ) ),
			array( 'foo_{type}_', array( 'type' => 'bar', ) ),
			array( '_foo_{type}', array( 'type' => 'bar', ) ),
			array( '{key}_bar_', array( 'key' => 'foo', ) ),
			array( '_{key}_bar', array( 'key' => 'foo', ) ),
			array( '_foo_bar_', array() ),
			array( '_{key}_{type}_', array( 'key' => 'foo', 'type' => 'bar', ) ),
			array( '{KEY}_BAR', array( 'KEY' => 'foo', ) ),
			array( 'FOO_{BAR}', array( 'TYPE' => 'bar', ) ),
			array( 'FOO_{BAR}_', array( 'TYPE' => 'bar', ) ),
			array( '_FOO_{BAR}', array( 'TYPE' => 'bar', ) ),
			array( '{KEY}_{BAR}', array( 'KEY' => 'foo', 'TYPE' => 'bar', ) ),
			array( '{KEY}_BAR_', array( 'KEY' => 'foo', ) ),
			array( '_{KEY}_BAR', array( 'KEY' => 'foo', ) ),
			array( '_FOO_{BAR}_', array( 'TYPE' => 'bar', ) ),
			array( '_{KEY}_BAR_', array( 'KEY' => 'foo', ) ),
			array( '_{KEY}_{BAR}_', array( 'KEY' => 'foo', 'TYPE' => 'bar', ) ),
			array( '_FOO_BAR_', array() ),
		);
	}
}