<?php

namespace WCM\AstroFields\PostMeta\Observers;

use WCM\AstroFields\Core\Observers\ViewAwareInterface;
use WCM\AstroFields\Core\Observers\ContextAwareInterface;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;
use WCM\AstroFields\Core\Receivers\FieldInterface;

use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;
use WCM\AstroFields\Core\Views\InputField as View;

use WCM\AstroFields\Core\Templates\TemplateInterface;


class InputField implements \SplObserver, ViewAwareInterface, ContextAwareInterface
{
	/** @var string */
	private $context = 'edit_form_advanced';

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