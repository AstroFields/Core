<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Core\Views\DataAwareInterface;

/**
 * Class SettingsSection
 * @package WCM\AstroFields\MetaBox\Commands
 * Written while waiting in the train on the station of beautiful Treibach-Althofen
 */
class SettingsSection implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'admin_head-options-{type}.php';

	/** @type string */
	private $key;

	/** @type array $types */
	private $types;

	/** @type string */
	private $title = '';

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

	public function __construct( $title, $id )
	{
		$this->title = $title;
		$this->id    = $id;

		# $this->view = new View;

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
var_dump( $data );
		$this->addSection();
	}

	/**
	 * Callback to add the meta box
	 */
	public function addSection()
	{
		foreach ( $this->types as $type )
		{
var_dump( $type );
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