<?php

namespace WCM\AstroFields\Security\Observers;

use WCM\AstroFields\Core\Observers\ContextAwareInterface;

class SanitizeString implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'sanitize_{type}_meta_{key}';

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
var_dump( func_get_args() );exit;
		/*return is_array( $value )
			? array_map( array( $this, 'sanitize' ), $value )
			: $this->sanitize( $value );*/
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
			FILTER_VALIDATE_URL,
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