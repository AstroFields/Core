<?php

namespace WCM\AstroFields\MetaBox\Mediators;

/**
 * Class MetaBox
 * @package WCM\AstroFields\Core\Mediators
 */
class MetaBox
{
	/** @type string */
	private $key;

	/** @type string */
	private $label;

	/** @type Array */
	private $types;

	/** @type string */
	private $context = 'advanced';

	/** @type string */
	private $priority = 'default';

	/** @type \SplPriorityQueue */
	private $entities;

	/**
	 * @param string $key
	 * @param string $label
	 * @param array $types
	 */
	public function __construct( $key, $label, Array $types )
	{
		$this->key   = $key;
		$this->label = $label;
		$this->types = $types;

		$this->entities = new \SplPriorityQueue;
		$this->entities->setExtractFlags( \SplPriorityQueue::EXTR_DATA );

		foreach ( $types as $type )
		{
			add_action( "add_meta_boxes_{$type}", array( $this, 'addMetaBox' ) );
		}
	}

	/**
	 * Set the `context` argument for `add_meta_box()`
	 * @param int $context
	 * @return $this
	 */
	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

	/**
	 * Set the `priority` argument for `add_meta_box()`
	 * @param int $priority
	 * @return $this
	 */
	public function setPriority( $priority )
	{
		$this->priority = $priority;

		return $this;
	}

	/**
	 * Callback to add the meta box
	 */
	public function addMetaBox()
	{
		foreach ( $this->types as $type )
		{
			add_meta_box(
				$this->key,
				$this->label,
				array( $this, 'notify' ),
				$type,
				$this->context,
				$this->priority
			);
		}
	}

	/**
	 * Attach a \SplSubject
	 * @param \SplSubject $command
	 * @param int         $priority
	 * @return $this|void
	 */
	public function attach( \SplSubject $command, $priority = 0 )
	{
		$this->entities->insert( $command, $priority );

		return $this;
	}

	/**
	 * Detach a \SplSubject
	 * @param \SplSubject $command
	 * @return $this|void
	 */
	public function detach( \SplSubject $command )
	{
		# $this->entities->detach( $command );

		return $this;
	}

	/**
	 * Render the MetaBox contents
	 * @param \WP_Post $post
	 * @param array    $data
	 */
	public function notify( \WP_Post $post, Array $data )
	{
		/*foreach ( $this->entities as $entity )
		{
			$this->entities
				->current()
				->notify();
		}*/
	}
}