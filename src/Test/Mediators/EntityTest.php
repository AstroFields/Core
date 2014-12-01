<?php

namespace WCM\AstroFields\Core\Test\Mediators;

use WCM\AstroFields\Core\Mediators\Entity;
use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class EntityTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * Tests if the key is of type `string`
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
	}

	/**
	 * Tests if overwriting the key throws an exception
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers       \WCM\AstroFields\Core\Mediators\Entity::setKey()
	 * @dataProvider getEntityInputData()
	 * @expectedException \LogicException
	 * @param string $key
	 * @param array  $types
	 */
	public function testSetKeyOverwritingException( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$entity->setKey( $key );
	}

	/**
	 * Tests if the key is of type `string`
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::setKey()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testSetKeyNotSetInConstructor( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( null, $types );

		$this->assertNull( $entity->getKey() );

		$entity->setKey( $key );
		$this->assertNotEmpty( $entity->getKey() );
		$this->assertInternalType( 'string', $entity->getKey() );

	}

	/**
	 * Tests if the Command storage is actually a \SplObjectstorage
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getCommands()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testCommandStorageContainerClass( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$this->assertInstanceOf( 'SplObjectstorage', $entity->getCommands() );
	}

	/**
	 * Tests if the provided data is valid and of the correct type
	 * and if the resulting types array is of the same length/size as the input data
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getTypes()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testSetTypesTypeAndSize( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$this->assertInternalType( 'array', $entity->getTypes() );
		$this->assertContainsOnly( 'string', $entity->getTypes() );
		$this->assertSameSize( $types, $entity->getTypes() );
	}

	/**
	 * Test if the class denies overwriting the set types and throws an \Exception
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::setTypes()
	 * @dataProvider getEntityInputData()
	 * @expectedException \LogicException
	 * @param string $key
	 * @param array  $types
	 */
	public function testSetTypesReturnedExceptionOnOverwrite( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$entity->setTypes( $types );
	}

	/**
	 * Test if the class works with no types were set in the constructor,
	 * but set later on using the public method.
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::setTypes()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getTypes()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testSetTypesWhenNotSetInConstructor( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key );

		$entity->setTypes( $types );
		$this->assertNotEmpty( $entity->getTypes() );
		$this->assertInternalType( 'array', $entity->getTypes() );
		$this->assertSameSize( $types, $entity->getTypes() );
	}

	/**
	 * Tests if the `addType()` function adds to the types stack
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getTypes()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::addType()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testAddSingleTypeToAlreadySetTypes( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );

		$this->assertSameSize( $types, $entity->getTypes() );

		foreach ( $types as $type )
			$entity->addType( $type );

		$this->assertNotSameSize( $types, $entity->getTypes() );
	}

	/**
	 * Tests if attaching a `\SplObserver`/Command works
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::attach()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::isDispatchable()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::notify()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::detach()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getCommands()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testAttachingAndDetachingASplObserverCommand( $key = '', Array $types = array() )
	{
		$info = array( 'proxy' => array( 'foo' => 'bar', ), );
		/** @var Entity */
		$entity = new Entity( $key, $types );
		/** @var ContextAwareInterface $command */
		$command = $this->getMockCommand();

		$entity->attach( $command, $info );

		$this->assertFalse( $entity->isDispatchable( $command ) );

		$this->assertInstanceOf( 'SplObjectstorage', $entity->getCommands() );
		$this->assertEquals( count( $entity->getCommands() ), 1 );
		$this->assertTrue( $entity->getCommands()->contains( $command ) );

		$entity->detach( $command );

		$this->assertEquals( count( $entity->getCommands() ), 0 );
		$this->assertFalse( $entity->getCommands()->contains( $command ) );
	}

	/**
	 * Tests if the `context` array build is done right
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getKey()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getTypes()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getCombinedData()
	 * @dataProvider getEntityInputData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testGetCombinedDataFromKeyTypesAndCustom( $key = '', Array $types = array() )
	{
		/** @var Entity */
		$entity = new Entity( $key, $types );
		$result = $entity->getCombinedData( array(
			'foo' => 'foo',
			'bar' => 'bar',
			'baz' => 'baz',
		) );

		$this->assertNotEmpty( $result );
		$this->assertInternalType( 'array', $result );
		$this->assertEquals( array(
			'foo' => 'foo',
			'bar' => 'bar',
			'baz' => 'baz',
			'key' => $entity->getKey(),
			'types' => $entity->getTypes(),
		), $result );
	}

	/**
	 * Test setting the `{proxy}` array via the getter
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::setProxy()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getProxy()
	 */
	public function testProxySetterAndGetter()
	{
		$entity = new Entity;
		$entity->setProxy( array( 'foo' => 'bar', ) );

		$this->assertNotEmpty( $entity->getProxy() );
		$this->assertInternalType( 'array', $entity->getProxy() );
	}

	/**
	 * Test setting the `{proxy}` array via the getter
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getProxy()
	 */
	public function testProxyGetterAsSetter()
	{
		$entity = new Entity;
		$info = array( 'proxy' => array( 'foo' => 'bar', ), );

		$entity->getProxy( $info );
		$this->assertNotEmpty( $entity->getProxy() );
		$this->assertInternalType( 'array', $entity->getProxy() );
		$this->assertEquals( $info['proxy'], $entity->getProxy() );
	}

	/**
	 * Tests if the attached `ParserInterface` is returning an array
	 * and if it is doing the parsing correct, returning usable data.
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::parseContext()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getCombinedData()
	 * @param string $key
	 * @param array  $types
	 */
	public function testContextParser( $key = '', Array $types = array() )
	{
		$info = array( 'proxy' => array( 'foo' => 'bar', ), );
		/** @var Entity $entity */
		$entity = new Entity( $key, $types );
		/** @var ContextAwareInterface $command */
		$command = $this->getMockCommandWithContext();

		$results = $entity->parseContext(
			$command->getContext(),
			$entity->getCombinedData( $info )
		);

		$this->assertInternalType( 'array', $results );

		foreach ( $results as $result )
			$this->assertRegExp( '/^([_a-z\{\}]*+)$/i', $result );
	}

	/**
	 * Tests if the `context` array build is done right
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::__construct()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getContextContainer()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getKey()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getTypes()
	 * @covers \WCM\AstroFields\Core\Mediators\Entity::getProxy()
	 * @param string $key
	 * @param array  $types
	 */
	public function testGetResultingContextContainer( $key = '', Array $types = array() )
	{
		$info = array( 'proxy' => array( 'foo' => 'bar', ), );
		/** @var Entity $entity */
		$entity = new Entity( $key, $types );
		$entity->getProxy( $info );

		$result = $entity->getContextContainer();

		$this->assertNotEmpty( $result );
		$this->assertInternalType( 'array', $result );
		$this->assertArrayHasKey( '{key}', $result );
		$this->assertArrayHasKey( '{type}', $result );
		$this->assertArrayHasKey( '{proxy}', $result );
	}

# ===== Helper

	public function getEntityInputData()
	{
		return array(
			array( 'test', array( 'post', 'page', ) ),
		);
	}

	public function getMockCommand()
	{
		$mock = $this->getMockBuilder( '\SplObserver' )
			->setMethods( array(
				'update',
				'getContext',
			) )
			->getMock();

		return $mock;
	}

	public function getMockCommandWithContext( \PHPUnit_Framework_MockObject_MockObject $mock = null )
	{
		is_null( $mock ) AND $mock = $this->getMockCommand();

		$mock
			->expects( $this->once() )
			->method( 'getContext' )
			->will( $this->returnValue( 'save_post_{type}' ) );

		return $mock;
	}

	public function getDispatchableMock()
	{
		$mock = $this->getMockBuilder( 'ContextAwareInterface' )
			->setMethods( array(
				'getContext',
			) )
			->getMock();

		$mock
			->expects( $this->once() )
			->method( 'getContext' )
			->will( $this->returnValue( 'save_post_{type}' ) );

		return $mock;
	}
}