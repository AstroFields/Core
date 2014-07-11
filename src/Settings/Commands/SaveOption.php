<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class SaveOption
	implements \SplObserver,
			   ContextAwareInterface
{
	/** @type string */
	private $context = 'pre_set_transient_settings_errors';

	/** @type Array */
	private $data;

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->data = $data;

		if (
			! isset( $_POST[ $data['key'] ] )
			OR empty( $_POST[ $data['key'] ] )
		)
			return;

		$updated = $this->save();
		$this->check( $updated );
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

	public function save()
	{
		return update_option(
			$this->data['key'],
			$_POST[ $this->data['key'] ]
		);
	}

	public function check( $updated )
	{
		if ( $updated )
			return;

		add_settings_error(
			$_POST[ $this->data['key'] ],
			$this->data['key'],
			sprintf(
				'Invalid value for %s',
				$this->data['key']
			)
		);
	}
}