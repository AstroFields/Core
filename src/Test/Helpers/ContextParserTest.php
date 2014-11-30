<?php

namespace WCM\AstroFields\Core\Test\Helpers;

class ContextParserTest extends \PHPUnit_Framework_TestCase
{
	/** @var \WCM\AstroFields\Core\Helpers\ContextParser */
	private $parser;

	public function setup()
	{
		$this->parser = new \WCM\AstroFields\Core\Helpers\ContextParser;
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
		$this->parser->setup( $input, $context );

		$result = $this->parser->getResult();

		$this->assertInternalType( 'array', $result );
		$this->assertEquals( $result, $expected );
	}

	public function getTestContext()
	{
		return array(
			array(
				'save_post_{type}',
				array(
					'{type}'  => array( 'post', 'page', ),
					'{proxy}' => array( 'edit', 'save', ),
				),
				array(
					'save_post_post',
					'save_post_page',
				),
			),
			array(
				'foo_bar_{key}',
				array(
					'{key}' => array( 'baz', 'dragon' ),
				),
				array(
					'foo_bar_baz',
					'foo_bar_dragon',
				),
			),
			array(
				'{proxy}_{type}',
				array(
					'{proxy}' => array( 'edit', 'save', ),
					'{type}'  => array( 'post', 'page', ),
				),
				array(
					'edit_post',
					'edit_page',
					'save_post',
					'save_page',
				),
			),
		);
	}
}