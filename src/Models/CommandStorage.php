<?php

namespace WCM\AstroFields\Core\Models;

use WCM\AstroFields\Core\Commands;

class CommandStorage extends \SplObjectStorage implements StorableInterface
{
	/**
	 * Attach a Command
	 * Appends `context` to the info in case the command has a context
	 * @param \SplObserver | Commands\ContextAwareInterface $command
	 * @param array                                         $info optional
	 */
	public function attach( $command, $info = null )
	{
		$this->isValid( $command );

		if ( $this->isContextAware( $command ) )
		{
			$info = $info + array(
				'context' => $command->getContext(),
			);
		}

		parent::attach( $command, $info );
	}

	/**
	 * @param \SplObjectStorage | CollectiblesInterface $collection
	 */
	/*public function addAll( CollectiblesInterface $collection = null )
	{
		if ( ! is_a( $collection, '\\SplObjectStorage' ) )
			throw new \InvalidArgumentException(
				'A CommandCollection must always extend \\SplObjectStorage'
			);

		parent::addAll( $collection );
	}*/

	/**
	 * Check if the command is a valid command and extends `\SplObserver`
	 * @throws \InvalidArgumentException If the attached class is no \SplObserver
	 * @param $command
	 * @return bool
	 */
	public function isValid( $command )
	{
		if ( ! $command instanceof \SplObserver )
			throw new \InvalidArgumentException( sprintf(
				'%s: %s must extend \\SplObserver',
				get_class( $this ),
				get_class( $command )
			) );
	}

	/**
	 * Test if a class is aware of its `context`
	 * @param $command
	 * @return array
	 */
	public function isContextAware( $command )
	{
		return class_implements(
			$command,
			'Commands\\ContextAwareInterface'
		);
	}

	/**
	 * The data associated with an object, minus the `context`
	 * @return mixed
	 */
	public function getInfo()
	{
		$this->rewind();
		$info = parent::getInfo();

		if ( isset( $info['context'] ) )
			unset( $info['context'] );

		return $info;
	}

	/**
	 * The `context` of an object if it exists, false otherwise
	 * @return string
	 */
	public function getContext()
	{
		$this->rewind();
		$info = parent::getInfo();

		return isset( $info['context'] )
			? $info['context']
			: '';
	}

	/**
	 * Retrieve the info/data associated with an object, minus the `context`
	 * @throws \OutOfRangeException If the requested object is not attached
	 * @param object $object
	 * @return array
	 */
	public function offsetGet( $object )
	{
		if ( ! $this->contains( $object ) )
			throw new \OutOfRangeException( sprintf(
				'The requested command `%s` does not exist in `%s`',
				$object,
				get_class( $this )
			) );

		$info = parent::offsetGet( $object );
		if ( isset( $info['context'] ) )
			unset( $info['context'] );

		return $info;
	}

	public function compare( $a, $b )
	{
		if ( $a === $b )
			return 0;

		return $a < $b
			? -1
			: 1;
	}
}