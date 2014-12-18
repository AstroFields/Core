<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Commands\CommandInterface;
use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Helpers\ParserInterface;

/**
 * Class Entity
 * @package WCM\AstroFields\Core\Mediators
 */
class Entity extends \SplObjectStorage implements EntityInterface
{
	/** @type string */
	private $key;

	/** @type Array */
	private $types = array();

	/** @type Array */
	private $proxy = array();

	/** @var ParserInterface | string */
	private $parser = '\\WCM\\AstroFields\\Core\\Helpers\\ContextParser';

	public function __construct(
		$key = null,
		Array $types = array(),
		ParserInterface $parser = null
		)
	{
		$this->key   = $key;
		$this->types = $types;

		$this->parser = (
			is_null( $parser )
			AND is_string( $this->parser )
		)
			? new $this->parser
			: new $parser;
	}

	public function getKey()
	{
		return $this->key;
	}

	/**
	 * Attach {proxy} placeholders, which are usable in the `context`
	 * Similar to {key} and {type}
	 * @param Array | mixed $proxy
	 * @return mixed|void
	 */
	public function setProxy( $proxy )
	{
		! is_array( $proxy ) and $proxy = array( $proxy );

		$this->proxy[] = $proxy;
	}

	public function __toString()
	{
		return sprintf( '%s@%s', __CLASS__, spl_object_hash( $this ) );
	}

	/**
	 * Attach a Command to an Entity
	 * This method also notifies the attached Command and
	 * attaches it to its `context` (filters/actions)
	 * Parses the Context with the Parser specific to this Entity.
	 * @throws \InvalidArgumentException
	 * @param CommandInterface $command
	 * @param array            $data
	 * @return $this|void
	 */
	public function attach( $command, $data = array() )
	{
		if ( ! $command instanceof CommandInterface )
			throw new \InvalidArgumentException( 'Commands must implement the CommandInterface' );

		if (
			! is_null( $data )
			and ! is_array( $data )
		)
			throw new \InvalidArgumentException( 'Command data must be an Array' );

		$data = $this->setupCommandData( $data );

		// Parse and attach context, notify Command and mark as notified
		if ( $this->isContextAware( $command ) )
		{
			/** @var ContextAwareInterface $command */
			$data = array_merge(
				$data,
				$this->parseContext( $command, $data )
			);

			$this->notify( $command, $data );
			$data['notified'] = true;
		}

		parent::attach( $command, $data );

		return $this;
	}

	/**
	 * Merge Command data with defaults and preserves defaults silently
	 * @param array $data
	 * @return array
	 */
	public function setupCommandData( Array $data )
	{
		return array(
			'key'      => $this->key,
			'types'    => $this->types,
			'notified' => false,
		) + $data;
	}

	/**
	 * Attach/Inject a Command bundle
	 * This method allows merging the Commands of one Entity
	 * into the current Entity. Already attached Commands do not get
	 * overwritten/are skipped. The Callbacks of the old Entity' Commands
	 * get removed from their respective filters and actions as keys and
	 * types are attached to the Entity and not the Command.
	 * @throws \InvalidArgumentException
	 * @param \SplObjectStorage $commands
	 */
	public function addAll( $commands )
	{
		if ( ! $commands instanceof \SplObjectStorage )
			throw new \InvalidArgumentException( 'Commands must implement SplObjectStorage' );

		/** @var \SplObjectStorage $commands */
		foreach ( $commands as $cmd )
		{
			if ( ! $commands->current() instanceof CommandInterface )
				throw new \InvalidArgumentException( 'Command must implement the CommandInterface' );

			/** @var CommandInterface $command */
			$command = $commands->current();
			if ( ! $this->contains( $command ) )
			{
				// Detach Command from old filters/actions
				if ( $this->isContextAware( $command ) )
				{
					$data = $commands->getInfo();
					foreach ( $data['context'] as $callback => $context )
						remove_filter( $context, $callback, 10  );
				}

				// Attach Commands to new filters
				$this->attach(
					$command,
					$commands->getInfo()
				);
			}
		}
	}

	/**
	 * Detach a Command
	 * Also removes its callbacks on filters or actions.
	 * @throws \InvalidArgumentException
	 * @param CommandInterface $command
	 * @return $this|void
	 */
	public function detach( $command )
	{
		if ( ! $command instanceof CommandInterface )
			throw new \InvalidArgumentException( 'Commands must implement the CommandInterface' );

		// Remove callbacks from filter/action
		if ( $this->isContextAware( $command ) )
		{
			$data = $this->offsetGet( $command );
			foreach ( $data['context'] as $callback => $context )
				remove_filter( $context, $callback, 10  );
		}

		parent::detach( $command );

		return $this;
	}

	/**
	 * Test if a class is aware of its `context`
	 * @param CommandInterface $command
	 * @return bool
	 */
	public function isContextAware( CommandInterface $command )
	{
		return $command instanceof ContextAwareInterface;
	}

	/**
	 * Build the context (hooks/filters) array
	 * When a context is provided when attaching a Command,
	 * you can use `{key}`, `{type}` and `{proxy}` as placeholders
	 * to be used in the Parser.
	 * @param ContextAwareInterface $command
	 * @param array            $data
	 * @return array
	 */
	public function parseContext( ContextAwareInterface $command, Array $data = array() )
	{
		$placeholder = array(
			'{key}'   => array( $this->key ),
			'{type}'  => $this->types,
			'{proxy}' => $this->proxy,
		);
		$this->parser->setup(
			$placeholder,
			$command->getContext()
		);

		return array_merge( $data, array(
			'context' => $this->parser->getResult(),
		) );
	}

	/**
	 * Delay the execution of a Command until the appearance of an action or filter
	 * Important: The Entity is attached as clone to avoid altering the original
	 * from inside a Command as this might affect other Commands.
	 * It also allows the method to be called multiple times.
	 * $subject = $this Alias:
	 * PHP 5.3 fix, as Closures don't know where to point `$this` prior to 5.4
	 * props Malte "s1lv3r" Witt
	 * @link https://wiki.php.net/rfc/closures/object-extension
	 * @codeCoverageIgnore
	 * @param ContextAwareInterface $command
	 * @param array             $data
	 */
	public function notify( ContextAwareInterface $command, Array $data = array() )
	{
		/** @var Entity | \SplObjectStorage $subject */
		$subject = clone $this;

		$callbacks = array();
		foreach ( $data['context'] as $index => $context )
		{
			/** @codeCoverageIgnore */
			$callback = function() use ( $command, $subject, $data )
			{
				$args = func_get_args();
				array_push( $args, $subject, $data );

				$subject->addInfo( array( 'frozen' => true, ), $command );

				/** @noinspection PhpVoidFunctionResultUsedInspection */
				return call_user_func_array(
					array( $command, 'update' ),
					$args
				);
			};

			// Set the hash of the {closure} as new index for the context
			$callbacks[] = _wp_filter_build_unique_id( $context, $callback, 10 );

			add_filter( $context, $callback, 10, PHP_INT_MAX -1 );
		}

		// Attach callback hashes to context array
		$data['context'] = array_combine( $callbacks, $data['context'] );

		$this->addInfo( $data, $command );
	}

	/**
	 * Add info to the info array of a Command
	 * @param array            $data
	 * @param ContextAwareInterface $command
	 */
	public function addInfo( Array $data, ContextAwareInterface $command = null )
	{
		if ( ! is_null( $command ) )
		{
			$this->rewind();
			while ( $this->valid() )
			{
				if ( $this->current() === $command )
					break;
				$this->next();
			}
		}

		$info = $this->getInfo() ?: array();
		parent::setInfo( array_merge( $info, $data ) );
	}
}
