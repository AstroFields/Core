<?php

namespace WCM\AstroFields\MetaBox\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;

use WCM\AstroFields\MetaBox\Commands\ViewAwareInterface;
use WCM\AstroFields\MetaBox\Views\MetaBoxView as View;

class MetaBox implements \SplObserver, ContextAwareInterface, ViewAwareInterface
{
	/** @type string */
	private $context = 'add_meta_boxes_{type}';

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

	/** @type ViewableInterface|DataAwareInterface */
	private $view;

	/** @type \SplPriorityQueue */
	private $receiver;

	/** @type TemplateInterface */
	private $template;

	public function __construct( $label, $context = 'advanced', $priority = 'default' )
	{
		$this->label      = $label;
		$this->mb_context = $context;
		$this->priority   = $priority;

		$this->view = new View;

		$this->receiver = new \SplPriorityQueue;
		$this->receiver->setExtractFlags( \SplPriorityQueue::EXTR_DATA );
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

		$this->view->setData( $this->receiver );
		$this->view->setTemplate( $this->template );

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
				array( $this->view, 'process' ),
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
		$this->receiver->insert( $command, $priority );

		return $this;
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

	public function setProvider( \SplPriorityQueue $receiver )
	{
		$this->receiver = $receiver;

		return $this;
	}

	public function setTemplate( TemplateInterface $template )
	{
		$this->template = $template;

		return $template;
	}
}