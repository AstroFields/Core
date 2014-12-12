<?php

namespace WCM\AstroFields\Core\Commands;

use WCM\AstroFields\Core\Providers;
use WCM\AstroFields\Core\Templates;

class ViewCmd implements
	CommandInterface,
	ViewAwareInterface,
	ContextAwareInterface
{
	/** @var string */
	protected $context = '';

	/** @type Providers\EntityProviderInterface */
	private $provider;

	/** @type Templates\TemplateInterface | Templates\PrintableInterface */
	private $template;

	public function __construct(
		Providers\DataProviderInterface $receiver,
		Templates\TemplateInterface $template
		)
	{
		$this->receiver = $receiver;
		$this->template = $template;
	}

	/**
	 * Receive update from subject
	 * @param CommandInterface $subject
	 * @param Array            $data
	 * @return mixed | void
	 */
	public function update( CommandInterface $subject = null, Array $data = array() )
	{
		$this->receiver->setData( $data );
		$this->template->attach( $this->receiver );

		echo $this->template->display();
	}

	public function setProvider( Providers\DataProviderInterface $provider = null )
	{
		$this->provider = $provider;

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