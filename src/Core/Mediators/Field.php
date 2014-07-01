<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Mediators\CommandStorageInterface;
use WCM\AstroFields\Core\Commands\CommandInterface;
use WCM\AstroFields\Core\Observers\ContextAwareInterface;

class Field implements \SplSubject
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
	 * @param \SplObserver $command
	 * @param array        $info
	 * @return $this
	 */
	public function attach( \SplObserver $command, Array $info = array() )
	{
		$this->commands->attach( $command, $info + array(
			'key'     => $this->key,
			'type'    => $this->types,
		) );

		return $this;
	}

	/**
	 * Build the context (hooks/filters) array
	 * When a context is provided when attaching a Command,
	 * you can use `{key}` and `{type}` as placeholder.
	 * @param  string $context
	 * @param  array  $info
	 * @return array
	 */
	protected function parseContext( $context, Array $info = array() )
	{
		# preg_match_all( '/[\{]{1}([\w]+)[\}]{1}/', $context, $matches );
		# `$matches[1]` contains all replacement strings
		# var_dump( $m );

		$result = array();
		foreach ( $this->types as $type )
		{
			$result[] = str_replace(
				array( "{type}", "{key}", ),
				array( $type, $this->key, ),
				$context
			);
		}

		return $result;
	}

	/**
	 * Detach an observer
	 * @param \SplObserver $command
	 * @return $this
	 */
	public function detach( \SplObserver $command )
	{
		$this->commands->detach( $command );

		return $this;
	}

	public function getCommands()
	{
		$commands = clone $this->commands;
		$commands->rewind();

		return $commands;
	}

	/**
	 * Notify an observer
	 * @return void
	 */
	public function notify()
	{
		$this->commands->rewind();
		foreach ( $this->commands as $o )
		{
			/** @var \SplObserver|ContextAwareInterface $cmd */
			$cmd  = $this->commands->current();
			$data = $this->commands->getInfo();
			if ( $cmd instanceof ContextAwareInterface )
			{
				$context = $this->parseContext( $cmd->getContext() );

				foreach ( $context as $c )
				{
					add_filter( $c, function() use ( $cmd, $data )
					{
						$cmd->update( $this, $data );
					} );
				}
			}
			else
			{
				$cmd->update( $this, $data );
			}
		}
	}
}