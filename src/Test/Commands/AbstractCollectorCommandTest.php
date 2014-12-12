<?php

namespace WCM\AstroFields\Core\Test\Commands;

use WCM\AstroFields\Core\Commands\AbstractCollectorCommand;
use WCM\AstroFields\Core\Commands\CommandInterface;

class AbstractCollectorCommandTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Test the return values of the `compare()` method
	 * @covers \WCM\AstroFields\Core\Commands\AbstractCollectorCommand::compare()
	 */
	public function testCompareReturnValues()
	{
		/** @var AbstractCollectorCommand $mock */
		$mock = $this->getAbstractCollectorMock();

		$this->assertEquals( -1, $mock->compare( 1, 2 ) );
		$this->assertEquals( 0, $mock->compare( 2, 2 ) );
		$this->assertEquals( 1, $mock->compare( 2, 1 ) );
	}

	/**
	 * Test if the Class inherits the assumed interfaces and parents
	 */
	public function testInheritedClassesAndInterface()
	{
		/** @var AbstractCollectorCommand $mock */
		$mock = $this->getAbstractCollectorMock();

		$this->assertInstanceOf( '\\SplPriorityQueue', $mock );
		$this->assertInstanceOf( '\\WCM\\AstroFields\\Core\\Commands\\CommandInterface', $mock );
	}

# ===== Mocks

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	public function getAbstractCollectorMock()
	{
		return $this->getMockForAbstractClass( '\\WCM\\AstroFields\\Core\\Commands\\AbstractCollectorCommand' );
	}
}