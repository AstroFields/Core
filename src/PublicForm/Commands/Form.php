<?php

namespace WCM\AstroFields\PublicForm\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Views\ViewableInterface;

use WCM\AstroFields\Core\Commands\ViewAwareInterface;
use WCM\AstroFields\PublicForm\Views\FormView as View;

class Form implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = '';

	/** @type string */
	private $key;

	/** @type array $types */
	private $types;

	/** @type ViewableInterface */
	private $view;

	/** @type \SplPriorityQueue */
	private $receiver;

	/** @type TemplateInterface */
	private $template;

	public function __construct()
	{
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

		$this->view->setData( $this->receiver );
		$this->view->setTemplate( $this->template );

		$this->view->process();
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

	public function setTemplate( TemplateInterface $template )
	{
		$this->template = $template;

		return $template;
	}
}