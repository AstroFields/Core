<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class Entity implements \SplSubject
{
	/** @var string */
	private $key;

	/** @var Array */
	private $types;

	/** @var \SplObjectstorage */
	private $commands;

	public function __construct( $key, Array $types )
	{
		$this->key   = $key;
		$this->types = $types;

		$this->commands  = new \SplObjectstorage;
	}

	/**
	 * Attach an SplObserver
	 * If the context is empty, but ContextAwareInterface implemented,
	 * the context was deliberately emptied to allow manual triggering from
	 * i.e. a Meta Box, an users profile, a custom form, etc.
	 * @param \SplObserver $command
	 * @param array        $info
	 * @return $this
	 */
	public function attach( \SplObserver $command, Array $info = array() )
	{
		$data = $info + array(
			'key'  => $this->key,
			'type' => $this->types,
		);

		if (
			$command instanceof ContextAwareInterface
			AND ! empty( $command->getContext() )
			)
		{
			$command->setContext( $this->parseContext(
				$command->getContext(),
				$data
			) );

			# @TODO Rethink if we can somehow still add the Command to the \SplObjectstorage
			$this->dispatch( $command, $data );

			return $this;
		}

		$this->commands->attach( $command, $data );

		return $this;
	}

	/**
	 * Build the context (hooks/filters) array
	 * When a context is provided when attaching a Command,
	 * you can use `{key}` and `{type}` as placeholder.
	 * @TODO In a future version AstroFields will allow custom replacements
	 * @param  string $context
	 * @param  array  $info
	 * @return array
	 */
	protected function parseContext( $context, Array $info = array() )
	{
		# @TODO Future version
		# preg_match_all( '/\{{1}([\w]+)\}{1}/', $context, $m );
		# `$matches[1]` contains all replacement strings
		# var_dump( $m[1] );

		$results = array();
		foreach ( $this->types as $type )
		{
			$results[] = str_replace(
				array( "{type}", "{key}", ),
				array( $type, $this->key, ),
				$context
			);
		}
		$results = array_filter( $results );
		$results = array_unique( $results );

		return $results;
	}

	/**
	 * Detach an Observer/a Command from the stack
	 * @param \SplObserver $command
	 * @return $this
	 */
	public function detach( \SplObserver $command )
	{
		$this->commands->detach( $command );

		# @TODO Remove from filter callback stack
		# foreach ( $command->getContext() as $c )
		# remove_filter( $c, array( $command, 'update' ) );

		return $this;
	}

	/**
	 * Retrieve all attached Commands
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
	 */
	public function notify()
	{
		$this->commands->rewind();
		foreach ( $this->commands as $o )
		{
			$this->commands->current()->update(
				$this,
				$this->commands->getInfo()
			);
		}
	}

	/**
	 * Delay the execution of a Command until the appearance of a hook or filter
	 * @param \SplObserver|ContextAwareInterface $command
	 * @param array                              $data
	 */
	public function dispatch( ContextAwareInterface $command, Array $data )
	{
		$contexts = $command->getContext();

		foreach ( $contexts as $context )
		{
			add_filter( $context, function() use ( $command, $data, $context )
			{
				// Provide all filter arguments to the Command
				$data['args'] = func_get_args();

				return $command->update(
					$this,
					$data
				);

			}, 10, PHP_INT_MAX -1 );
		}
	}
}