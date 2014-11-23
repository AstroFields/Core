<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Receivers\DataReceiverInterface;
use WCM\AstroFields\Core\Receivers\EntityProviderInterface;

use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;
use WCM\AstroFields\Core\Views\BaseView;

use WCM\AstroFields\Core\Templates\TemplateInterface;


class ViewCmd implements \SplObserver, ViewAwareInterface, ContextAwareInterface
{
	/** @var string */
	protected $context = '';

	/** @type ViewableInterface|DataAwareInterface */
	private $view;

	/** @type EntityProviderInterface */
	private $receiver;

	/** @type TemplateInterface */
	private $template;

	public function __construct()
	{
		$this->view = new BaseView;
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

	public function setProvider( DataReceiverInterface $receiver )
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