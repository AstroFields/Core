<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Commands\ViewAwareInterface;
use WCM\AstroFields\Core\Commands\ContextAwareInterface;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;
use WCM\AstroFields\Core\Receivers\FieldInterface;

use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;
use WCM\AstroFields\Core\Views\Field as View;

use WCM\AstroFields\Core\Templates\TemplateInterface;


class ViewCmd implements \SplObserver, ViewAwareInterface, ContextAwareInterface
{
	/** @var string */
	protected $context = '';

	/** @type ViewableInterface|DataAwareInterface */
	private $view;

	/** @type FieldInterface */
	private $receiver;

	/** @type TemplateInterface */
	private $template;

	public function __construct()
	{
		$this->view = new View;
	}

	/**
	 * Receive update from subject
	 * @param \SplSubject $subject
	 * @param Array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->view->setTemplate( $this->template );

		$this->receiver->setData( $data );
		$this->view->setData( $this->receiver );

		$this->view->process();
	}

	public function setProvider( DataProviderInterface $receiver )
	{
		$this->receiver = $receiver;

		return $this;
	}

	public function setTemplate( TemplateInterface $template )
	{
		$this->template = $template;

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
}