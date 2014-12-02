<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Helpers\ContextParser;
use WCM\AstroFields\Core\Models\EntityStorage;

class Entity implements \SplSubject
{
	/** @type string */
	private $key;

	/** @type Array */
	private $types = array();

	/** @type Array */
	private $proxy = array();

	/** @type \SplObjectstorage */
	private $commands;

	/**
	 * @param string $key
	 * @param array  $types
	 */
	public function __construct( $key = '', Array $types = array() )
	{
		$this->key   = $key;
		$this->types = $types;

		$this->commands = new EntityStorage;
		#$this->commands = new \SplObjectstorage;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Set a name for the Entity
	 * @param string $key
	 * @throws \LogicException
	 */
	public function setKey( $key )
	{
		if ( ! empty( $this->key ) )
			throw new \LogicException( 'An entity can only be named once' );

		$this->key = $key;
	}

	/**
	 * Attach types to this entity
	 * @TODO Freeze at one point
	 * @param  array $types
	 * @throws \LogicException
	 */
	public function setTypes( Array $types )
	{
		if ( ! empty( $this->types ) )
			throw new \LogicException( 'To add additional types, use `addType()`' );

		$this->types = $types;
	}

	/**
	 * Attach an additional type
	 * @TODO Freeze at one point
	 * @param $type
	 */
	public function addType( $type )
	{
		$this->types[] = $type;
	}

	/**
	 * @return Array
	 */
	public function getTypes()
	{
		return $this->types;
	}

	/**
	 * Attach an SplObserver
	 * Note: If the context is empty, but ContextAwareInterface implemented,
	 * the context was deliberately emptied to allow manual triggering from
	 * i.e. a Meta Box, an users profile, a custom form, etc.
	 * @codeCoverageIgnore
	 * @param \SplObserver | ContextAwareInterface $command
	 * @param array                                $info
	 * @return $this
	 */
	public function attach( \SplObserver $command, Array $info = array() )
	{
		$data = $this->getCombinedData( $info );

		#$this->commands->attach( $command, $data );
		#var_dump( ' >> Info', $this->commands->getInfo() );
		#var_dump( ' >> Context', $this->commands->getContext() );
		#foreach ( $this->commands as $command )
		#	var_dump( $this->commands->current() );

		if ( $this->isDispatchable( $command ) )
		{
			// Do not try to parse an already parsed context
			// This is the case if the same command gets attached multiple times
			if ( ! is_array( $command->getContext() ) )
			{
				// Build the context by replacing {placeholders} with real data
				$command->setContext( $this->parseContext(
					$command->getContext(),
					$data
				) );
			}

			$this->dispatch( $command, $data );

			return $this;
		}

		$this->commands->attach( $command, $data );

		return $this;
	}

	/**
	 * Add custom data and append the key and types
	 * This effectively overwrites manually set `key` and `types`
	 * preventing errors from accidental abuse of those reserved keys.
	 * @throws \UnexpectedValueException If reserved keys are used in the data array
	 * @param array $info Optional
	 * @return array
	 */
	public function getCombinedData( Array $info = array() )
	{
		if (
			isset( $info['key'] )
			OR isset( $info['types'] )
		)
			throw new \UnexpectedValueException( sprintf(
				'%s: `key` and `types` are reserved keys',
				get_class( $this )
			) );

		return $info + array(
			'key'   => $this->getKey(),
			'types' => $this->getTypes(),
		);
	}

	/**
	 * Can the command get dispatched?
	 * Dispatching means attaching it to a hook or filter.
	 * This is only possible if the Command implements
	 * the ContextAwareInterface methods and getContext()
	 * actually returns something. A commands context can
	 * get emptied before it gets attached to remove it
	 * from the stack of delayed/hooked command storage.
	 * @param \SplObserver $command
	 * @return bool
	 */
	public function isDispatchable( \SplObserver $command )
	{
		return
			$command instanceof ContextAwareInterface
			AND '' !== $command->getContext();
	}

	/**
	 * Attach {proxy} placeholders, usable in the `context`
	 * similar to {key} and {type}
	 * @param array $proxy
	 * @return $this
	 */
	public function setProxy( Array $proxy )
	{
		$this->proxy = $proxy;

		return $this;
	}

	/**
	 * Retrieve the {proxy} values
	 * Allow passing the {proxy} as part of the data/info Array
	 * to set it during initial setup of the Entity.
	 * Does not allow overwriting the {proxy} when it already is set.
	 * If there is demand to set the {proxy} on the fly,
	 * use the `setProxy()` method.
	 * @param array $info Optional
	 * @return Array
	 */
	public function getProxy( Array $info = array() )
	{
		// Use the Setter
		if (
			empty( $this->proxy )
			AND isset( $info['proxy'] )
		)
			$this->setProxy( $info['proxy'] );

		return $this->proxy;
	}

	/**
	 * Build the context (hooks/filters) array
	 * When a context is provided when attaching a Command,
	 * you can use `{key}`, `{type}` and `{proxy}` as placeholder.
	 * @param  string $context Retrieved from a Command
	 * @param  array  $info key/value storage of placeholders
	 * @return array The parsed/possible contexts
	 */
	public function parseContext( $context, Array $info = array() )
	{
		// Allow passing the {proxy} as part of the data/info Array
		// Utitlizes the Getter to use type hinting in case it's no Array.
		$this->getProxy( $info );

		// @TODO Allow exchanging the parser
		$parser = new ContextParser;

		$parser->setup(
			$this->getContextContainer(),
			$context
		);

		return $parser->getResult();
	}

	/**
	 * Retrieve the container of all context, ready to get parsed
	 * into filter or action names used to attach callbacks.
	 * @return array
	 */
	public function getContextContainer()
	{
		return array(
			'{key}'   => array( $this->getKey() ),
			'{type}'  => $this->getTypes(),
			'{proxy}' => $this->getProxy(),
		);
	}

	/**
	 * Detach an Observer/a Command from the stack
	 * @param \SplObserver $command
	 * @return $this
	 */
	public function detach( \SplObserver $command )
	{
		$this->commands->detach( $command );

		return $this;
	}

	/**
	 * Retrieve all attached Commands.
	 * Command storage is returned as clone to avoid altering the original.
	 * @return \SplObjectstorage
	 */
	public function getCommands()
	{
		$commands = clone $this->commands;
		$commands->rewind();

		return $commands;
	}

	/**
	 * Notify all attached Commands to execute
	 * $subject = $this Alias:
	 * PHP 5.3 fix, as Closures don't know where to point $this prior to 5.4
	 * props Malte "s1lv3r" Witt
	 * @codeCoverageIgnore
	 */
	public function notify()
	{
		$subject = $this;

		$this->commands->rewind();
		foreach ( $this->commands as $command )
		{
			$this->commands->current()->update(
				$subject,
				$this->commands->getInfo()
			);
		}
	}

	/**
	 * Delay the execution of a Command until the appearance of a hook or filter
	 * $subject = $this Alias:
	 * PHP 5.3 fix, as Closures don't know where to point $this prior to 5.4
	 * props Malte "s1lv3r" Witt
	 * @link https://wiki.php.net/rfc/closures/object-extension
	 * @codeCoverageIgnore
	 * @param \SplObserver | ContextAwareInterface $command
	 * @param array                                $data
	 */
	public function dispatch( ContextAwareInterface $command, Array $data )
	{
		$contexts = $command->getContext();
		$subject  = $this;

		foreach ( $contexts as $context )
		{
			# @codeCoverageIgnore
			add_filter( $context, function() use ( $subject, $command, $data, $context )
			{
				// Provide all filter arguments to the Command as `args` Array
				$data['args'] = func_get_args();

				return $command->update(
					$subject,
					$data
				);
				# Less readable version:
				# return call_user_func_array( array( $command, 'update' ), func_get_args() );

			}, 10, PHP_INT_MAX -1 );
		}
	}
}