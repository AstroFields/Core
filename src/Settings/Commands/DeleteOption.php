<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class DeleteOption
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
		$this->data   = $data;

		if (
			empty( $_POST[ $data['key'] ] )
			AND '' === get_option( $data['key'] )
		)
			$this->delete();
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

	public function delete()
	{
		return delete_option( $this->data['key'] );
	}
}