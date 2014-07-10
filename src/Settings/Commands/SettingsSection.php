<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Core\Mediators\Entity;
use WCM\AstroFields\Core\Commands\ContextAwareInterface;
use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Views\ViewableInterface;
use WCM\AstroFields\Settings\Views\SettingsSection as View;

/**
 * Class SettingsSection
 * @package WCM\AstroFields\MetaBox\Commands
 * Written while waiting in the train on the station of beautiful Treibach-Althofen/Carinthia
 */
class SettingsSection implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'load-options-{type}.php';
	# private $context = 'admin_head-options-{type}.php';

	/** @type string */
	private $key;

	/** @type array $types */
	private $types;

	/** @type string */
	private $title;

	/** @type ViewableInterface */
	private $view;

	/** @type \SplPriorityQueue */
	private $receiver;

	/** @type TemplateInterface */
	private $template;

	public function __construct( $title = '' )
	{
		$this->title = $title;

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

		$this->addSection();
	}

	/**
	 * Callback to add the meta box
	 */
	public function addSection()
	{
		foreach ( $this->types as $type )
		{
			add_settings_section(
				$this->key,
				$this->title,
				array( $this->view, 'process' ),
				$type
			);
		}
#var_dump( $GLOBALS['wp_settings_sections'] );
	}

	/**
	 * Attach a \SplSubject
	 * Adds the entity name to the whitelist so WP core can
	 * care about saving the option value(s).
	 * @param \SplSubject $command
	 * @param int         $priority
	 * @return $this|void
	 */
	public function attach( \SplSubject $command, $priority = 0 )
	{
		$this->receiver->insert( $command, $priority );

		/** @type Entity $command */
		foreach ( $command->getTypes() as $type )
		{
			register_setting( $type, $command->getKey() );
			add_settings_field(
				$command->getKey(),
				'Foo',
				array( $this->view, 'process' ),
				$type,
				'default'
			);
		}

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