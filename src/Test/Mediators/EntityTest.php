<?php

namespace WCM\AstroFields\Core\Test\Mediators;

use WCM\AstroFields\Core\Mediators\Entity;
use WCM\AstroFields\Core\Commands\CommandInterface;
use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class EntityTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests if the key is of type `string` and if the right key gets set
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getKey()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testGetKeySetInConstructor( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$this->assertInternalType( 'string', $entity->getKey() );

		$this->assertEquals( 'test', $entity->getKey() );
	}

	/**
	 * Test if the Entity throws an Exception if no CommandInterface was attached
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::attach()
	 * @expectedException \InvalidArgumentException
	 */
	public function testAttachNoCommandExceptions()
	{
		/** @var Entity $entity */
		$entity = new Entity( null, array() );
		$entity->attach( new \stdClass, array() );
	}

	/**
	 * Test if the Entity throws an Exception if the wrong data type was attached
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::attach()
	 * @expectedException \InvalidArgumentException
	 */
	public function testAttachWrongDataTypeExceptions()
	{
		/** @var CommandInterface $mock */
		$mock = $this->getMock( '\\WCM\\AstroFields\\Core\\Commands\\CommandInterface' );

		/** @var Entity $entity */
		$entity = new Entity( null, array() );
		$entity->attach( $mock, 1 );
	}

	/**
	 * Test if attaching a Command (that has a context) to the Entity works
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::attach()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::parseContext()
	 * @param string $key
	 * @param array  $types
	 */
	public function testAttachAndContainsContextAwareCommand( $key = '', Array $types = array() )
	{
		/** @var CommandInterface | \PHPUnit_Framework_MockObject_MockObject $stub */
		$stub = $this->getMockBuilder( '\\WCM\\AstroFields\\Core\\Commands\\ContextAwareInterface' )
			->setMethods( array(
				'update',
				'setContext',
				'getContext',
			) )
			->getMock();

		$stub->expects( $this->once() )
			->method( 'getContext' )
			->will( $this->returnValue( 'save_post_{type}' ) );

		/** @var Entity $entity */
		$entity = new Entity( $key, $types );
		$entity->attach( $stub, array( 'foo' => 'bar', ) );
		$this->assertTrue( $entity->contains( $stub ) );

		$expected = $entity->offsetGet( $stub );
		$this->assertEquals( $expected, array(
			'key'      => $key,
			'types'    => $types,
			'foo'      => 'bar',
			'context'  => $expected['context'],
			'notified' => true,
		) );
	}

	/**
	 * Test if attaching a Command to the Entity works
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::attach()
	 * @param string $key
	 * @param array  $types
	 */
	public function testAttachAndContainsNoContextCommand( $key = '', Array $types = array() )
	{
		/** @var CommandInterface | \PHPUnit_Framework_MockObject_MockObject $stub */
		$stub = $this->getMockBuilder( '\\WCM\\AstroFields\\Core\\Commands\\CommandInterface' )
			->setMethods( array(
				'update',
			) )
			->getMock();

		/** @var Entity $entity */
		$entity = new Entity( $key, $types );
		$entity->attach( $stub, array( 'foo' => 'bar', ) );

		$this->assertTrue( $entity->contains( $stub ) );

		$this->assertEquals( $entity->offsetGet( $stub ), array(
			'key'      => $key,
			'types'    => $types,
			'foo'      => 'bar',
			'notified' => false,
		) );
	}

	/**
	 * Test if default Command/Entity data gets preserved
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::setupCommandData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testDefaultCommandDataPreserved( $key = '', Array $types = array() )
	{
		$custom = array(
			'key' => 'foo',
			'types' => 'bar',
			'notified' => 'baz',
		);
		/** @var Entity $entity */
		$entity = new Entity( $key, $types );

		$result = $entity->setupCommandData( $custom );

		$this->assertNotEquals( $custom, $result );
		$this->assertEquals( array(
			'key'      => $key,
			'types'    => $types,
			'notified' => false,
		), $result );
	}

	/**
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::detach()
	 * @expectedException \InvalidArgumentException
	 */
	public function testAddAllException()
	{
		$entity = new Entity( 'test', array() );
		$storage = new \SplObjectStorage;
		$storage->attach( new \stdClass );
		$entity->addAll( $storage );
	}

	/**
	 * Test if merging two entities works and the Entity holds the new Commands
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::addAll()
	 * @param string $key
	 * @param array  $types
	 */
	public function testAddAllAttachesCommands( $key = '', Array $types = array() )
	{
		$entity = new Entity( $key, $types );
		$toMerge = new Entity( 'foo', array( 'bar', 'baz', ) );
		/** @var CommandInterface $cmd */
		$cmd = $this->getMock( '\\WCM\\AstroFields\\Core\\Commands\\CommandInterface' );

		$toMerge->rewind();
		// Test if the first entity only has the Command
		$toMerge->attach( $cmd, array() );
		$toMerge->rewind();

		$this->assertEquals( 1, $toMerge->count() );
		$this->assertEquals( 0, $entity->count() );

		// Test if the Command gets added
		$entity->addAll( $toMerge );

		$this->assertEquals( 1, $entity->count() );

		// Test if the Command does not get added a second time
		$entity->addAll( $toMerge );
		$this->assertEquals( 1, $entity->count() );
	}

	/**
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::detach()
	 * @expectedException \InvalidArgumentException
	 */
	public function testDetachException()
	{
		$entity = new Entity( 'test', array() );
		$entity->detach( new \stdClass );
	}

	/**
	 * Test if removing Commands works
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::detach()
	 * @param string $key
	 * @param array  $types
	 */
	public function testDetachSingleCommand( $key = '', Array $types = array() )
	{
		$entity = new Entity( $key, $types );
		/** @var CommandInterface $mock */
		$mock = $this->getMock( '\\WCM\\AstroFields\\Core\\Commands\\CommandInterface' );

		// Test if attaching works
		$entity->attach( $mock );
		$this->assertEquals( 1, $entity->count() );
		// Test if detaching works
		$entity->detach( $mock );
		$this->assertEquals( 0, $entity->count() );
	}

	/**
	 * Test if a Command is aware of its context
	 * Assumes that the command implements `ContextAwareInterface`
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::isContextAware()
	 */
	public function testIfCommandIsContextAware()
	{
		/** @var Entity $entity */
		$entity = new Entity( 'test', array() );

		/** @var CommandInterface | \PHPUnit_Framework_MockObject_MockObject $stub */
		$stub = $this->getMock( '\\WCM\\AstroFields\\Core\\Commands\\ContextAwareInterface' );

		$this->assertTrue( $entity->isContextAware( $stub ) );
	}

	/**
	 * Test if the default Parser works
	 * @dataProvider getEntityInputData()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::parseContext()
	 * @param string $key
	 * @param array  $types
	 */
	public function testParseContext( $key = '', Array $types = array() )
	{
		/** @var ContextAwareInterface | \PHPUnit_Framework_MockObject_MockObject $stub */
		$stub = $this->getMockBuilder( '\\WCM\\AstroFields\\Core\\Commands\\ContextAwareInterface' )
			->setMethods( array(
				'update',
				'setContext',
				'getContext',
			) )
			->getMock();

		$stub->expects( $this->once() )
			->method( 'getContext' )
			->will( $this->returnValue( 'sanitize_{type}_meta_{key}' ) );

		/** @var Entity $entity */
		$entity = new Entity( $key, $types );
		$results = $entity->parseContext(
			$stub,
			$entity->setupCommandData( array( 'foo' => 'baz', )
		) );
		$result = $results['context'];

		$this->assertInternalType( 'array', $result );

		foreach ( $result as $r )
			$this->assertRegExp( '/^([_a-z\{\}]*+)$/i', $r );

		$expected = array(
			'sanitize_post_meta_test',
			'sanitize_page_meta_test',
		);
		$this->assertEquals( $expected, $result );
	}

	/**
	 * Test if callbacks get attached to the filters/actions
	 * @dataProvider getEntityInputData()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::notify()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::addInfo()
	 * @param string $key
	 * @param array  $types
	 */
	public function testNotifyAddsCallbackToFilter( $key = '', Array $types = array() )
	{
		/** @var Entity $entity */
		$entity = new Entity( $key, $types );

		/** @var ContextAwareInterface | \PHPUnit_Framework_MockObject_MockObject $stub */
		$stub = $this->getMockBuilder( '\\WCM\\AstroFields\\Core\\Commands\\ContextAwareInterface' )
			->setMethods( array(
				'update',
				'setContext',
				'getContext',
			) )
			->getMock();

		$stub->expects( $this->once() )
			->method( 'getContext' )
			->will( $this->returnValue( 'sanitize_{type}_meta_{key}' ) );

		$entity->attach( $stub );
		// Make sure the Entity actually contains the command
		$this->assertTrue( $entity->contains( $stub ) );
	}

# ===== Mocks & Sample Data

	public function getEntityInputData()
	{
		return array(
			array( 'test', array( 'post', 'page', ) ),
		);
	}

	public function getCommandMock()
	{
		$mock = $this->getMockBuilder( '\SampleCommand' )
			->setMethods( array() )
			->getMock();

		return $mock;
	}
}
