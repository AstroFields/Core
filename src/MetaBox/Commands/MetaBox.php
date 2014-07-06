<?php

namespace WCM\AstroFields\MetaBox\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class MetaBox implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'add_meta_boxes_{type}';

	/** @type \SplPriorityQueue */
	private $entities;

	/** @type string */
	private $key;

	/** @type array $types */
	private $types;

	/** @type string */
	private $label = 'Foo';

	/** @type string */
	private $mb_context;

	/** @type string */
	private $priority;

	public function __construct( $label, $context = 'advanced', $priority = 'default' )
	{
		$this->label      = $label;
		$this->mb_context = $context;
		$this->priority   = $priority;

		$this->entities = new \SplPriorityQueue;
		$this->entities->setExtractFlags( \SplPriorityQueue::EXTR_DATA );
	}

	/**
	 * Receive update from subject
	 * @param \SplSubject $subject
	 * @param Array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->key   = $data['key'];
		$this->types = $data['type'];
		# $post        = $data['args'][0];

		$this->addMetaBox();
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
				$this->mb_context,
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

	public function notify( \WP_Post $post, Array $data )
	{
		foreach ( $this->entities as $entity )
		{
			$this->entities
				->current()
				->notify();
		}
	}

	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

	public function getContext()
	{
		return $this->context;
	}
}