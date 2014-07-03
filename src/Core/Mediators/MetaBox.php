<?php

namespace WCM\AstroFields\Core\Mediators;

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

	public function __construct( $key, $label, Array $types )
	{
		$this->key   = $key;
		$this->label = $label;
		$this->types = $types;

		$this->entities = new \SplObjectstorage;

		add_action( 'load-post-new.php', array( $this, 'addMetaBox' ) );
		add_action( 'load-post.php', array( $this, 'addMetaBox' ) );
	}

	/**
	 * @param int $context
	 * @return $this
	 */
	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

	/**
	 * @param int $priority
	 * @return $this
	 */
	public function setPriority( $priority )
	{
		$this->priority = $priority;

		return $this;
	}

	public function addMetaBox()
	{
		add_meta_box(
			$this->key,
			$this->label,
			array( $this, 'notify' ),
			null,
			$this->context,
			$this->priority
		);
	}

	/**
	 * Attach a \SplSubject
	 * @param \SplSubject $command
	 * @param int|null     $priority
	 * @return $this|void
	 */
	public function attach( \SplSubject $command, $priority = null )
	{
		$this->entities->attach( $command );

		return $this;
	}

	/**
	 * Detach a \SplSubject
	 * @param \SplSubject $command
	 * @return $this|void
	 */
	public function detach( \SplSubject $command )
	{
		$this->entities->detach( $command );

		return $this;
	}

	/**
	 * Render the MetaBox contents
	 * @param \WP_Post $post
	 * @param array    $data
	 */
	public function notify( \WP_Post $post, Array $data )
	{
		foreach ( $this->entities as $entity )
		{
			$this->entities->current()->notify();
		}
	}
}