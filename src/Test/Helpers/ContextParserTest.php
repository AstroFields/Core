<?php

namespace WCM\AstroFields\Core\Test\Helpers;

use WCM\AstroFields\Core\Mediators\Entity;
use WCM\AstroFields\Core\Helpers\ContextParser;

class ContextParserTest extends \PHPUnit_Framework_TestCase
{
	private $parser;

	public function setup()
	{
		$this->parser = new ContextParser;
	}

	/**
	 * @covers ContextParser::setup()
	 * @dataProvider getTestContext()
	 */
	public function testSetup( $context, Array $input, $expected )
	{
		$this->assertInternalType( 'array', $input );
		$this->assertInternalType( 'string', $context );
	}

	/**
	 * @covers ContextParser::getResult()
	 * @dataProvider getTestContext()
	 */
	public function testResult( $context, Array $input, $expected )
	{
		$parser = new ContextParser;
		$parser->setup( $input, $context );

		$result = $parser->getResult();
		$this->assertInternalType( 'array', $result );
		var_dump( $result, $expected );
		$this->assertEquals( $result, $expected );
		#$this->assertContains( '', $result );
	}

	public function getTestContext()
	{
		return array(
			array(
				'save_post_{type}',
				array(
					'{key}'   => array( 'foobar', ),
					'{type}'  => array( 'post', 'page', ),
					'{proxy}' => array( 'edit', 'save', ),
				),
				array( 'save_post_post', 'save_post_page', ),
			),
		);
	}
}