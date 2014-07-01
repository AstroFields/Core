<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Observers\ViewAwareInterface;
use WCM\AstroFields\Core\Filters\ViewableFilterIterator;

class MetaBox # implements \SplSubject
{
	/** @var string */
	private $key;

	/** @var string */
	private $label;

	/** @var string */
	private $context = 'advanced';

	/** @var string */
	private $priority = 'default';

	/** @var \SplObjectstorage */
	private $observers;

	public function __construct( $key, $label )
	{
		$this->key   = $key;
		$this->label = $label;

		$this->observers = new \SplObjectstorage;

		add_action( 'load-post-new.php', array( $this, 'addMetaBox' ) );
	}

	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

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
			$this->priority,
			$this->getObservers()
		);
	}

	/**
	 * Attach a \SplObserver
	 * @param \SplObserver $observer
	 * @return $this|void
	 */
	public function attach( $observer )
	{
		$this->observers->attach( $observer );

		return $this;
	}

	/**
	 * Detach a \SplObserver
	 * @param \SplObserver $observer
	 * @return $this|void
	 */
	public function detach( \SplObserver $observer )
	{
		$this->observers->detach( $observer );

		return $this;
	}

	/**
	 * @return \ArrayObject
	 */
	public function getObservers()
	{
		$observers = clone $this->observers;
		$observers->rewind();

		return $observers;
	}

	/**
	 * Render the MetaBox contents
	 * @param \WP_Post $post
	 * @param array    $data
	 */
	public function notify( \WP_Post $post, Array $data )
	{
		/** @type $fields \SplObjectstorage */
		/** @type $commands \SplObjectstorage */
		# $fields = $this->getObservers();
		$fields = $data['args'];
		foreach ( $fields as $o )
		{
			$commands = $fields
				->current()
				->getCommands();
			$commands = new ViewableFilterIterator( $commands );

			foreach ( $commands as $command )
			{
				$commands
					->current()
					->update(
						$fields->current(),
						$commands->getInfo()
					);
			}
		}
	}
}