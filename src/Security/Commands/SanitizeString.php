<?php

namespace WCM\AstroFields\Security\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class SanitizeString implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'sanitize_{type}_meta_{key}';

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 * @return array|string|null
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$value = $data['args'][0];
		if ( empty( $value ) )
			return $value;

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
			FILTER_SANITIZE_STRING,
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