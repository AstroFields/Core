<?php

namespace WCM\AstroFields\Core\Test\Mediators;

class CommandInterfaceTest extends \PHPUnit_Framework_TestCase
{
	public function testCommandInterface()
	{
		$mock = $this->getMock( 'CommandInterface' );
		$this->assertInstanceOf( 'CommandInterface', $mock );
	}
}