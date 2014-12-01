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
	 * @covers \WCM\AstroFields\Core\Helpers\ContextParser::setup()
	 * @dataProvider getSampleData()
	 */
	public function testSetupInputValueTypes( $context, Array $input, $expected )
	{
		$this->assertInternalType( 'array', $input );
		$this->assertInternalType( 'string', $context );
		$this->assertRegExp( '/^([_a-z\{\}]*+)$/i', $context );
	}

	/**
	 * @covers \WCM\AstroFields\Core\Helpers\ContextParser::getResult()
	 * @dataProvider getSampleData()
	 */
	public function testResultTypeIsArray( $context, Array $input, $expected )
	{
		$this->parser->setup( $input, $context );
		$this->assertInternalType( 'array', $this->parser->getResult() );
	}

	/**
	 * @covers \WCM\AstroFields\Core\Helpers\ContextParser::getResult()
	 * @dataProvider getSampleData()
	 */
	public function testResultArrayEqualsExpectedArray( $context, Array $input, $expected )
	{
		$this->parser->setup( $input, $context );
		$this->assertEquals( $this->parser->getResult(), $expected );
	}

	public function getSampleData()
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