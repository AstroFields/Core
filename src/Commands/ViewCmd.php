<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Receivers;
use WCM\AstroFields\Core\Templates;

class ViewCmd implements \SplObserver, ViewAwareInterface, ContextAwareInterface
{
	/** @var string */
	protected $context = '';

	/** @type Receivers\EntityProviderInterface */
	private $receiver;

	/** @type Templates\TemplateInterface | Templates\PrintableInterface */
	private $template;

	public function __construct(
		Receivers\DataReceiverInterface $receiver,
		Templates\TemplateInterface $template )
	{
		$this->receiver = $receiver;
		$this->template = $template;
	}

	/**
	 * Receive update from subject
	 * @param \SplSubject $subject
	 * @param Array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->receiver->setData( $data );
		$this->template->attach( $this->receiver );

		echo $this->template;
	}

	public function setProvider( Receivers\DataReceiverInterface $receiver )
	{
		$this->receiver = $receiver;

		return $this;
	}

	public function setTemplate( Templates\TemplateInterface $template )
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