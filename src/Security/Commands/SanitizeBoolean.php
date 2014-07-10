<?php

namespace WCM\AstroFields\Security\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class SanitizeBoolean implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	protected $context = 'sanitize_{type}_meta_{key}';

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 * @return array|string|null
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$value = $data['args'][0];

		return is_array( $value )
			? array_map( array( $this, 'sanitize' ), $value )
			: $this->sanitize( $value );
	}

	/**
	 * Sanitize Callback
	 * @param  mixed $value
	 * @return mixed|null
	 */
	public function sanitize( $value )
	{
		return filter_var(
			$value,
			FILTER_VALIDATE_BOOLEAN,
			array( 'flags' => FILTER_NULL_ON_FAILURE )
		);
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