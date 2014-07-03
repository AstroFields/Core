<?php

namespace WCM\AstroFields\PostMeta\Observers;

use WCM\AstroFields\Core\Observers\ContextAwareInterface;

class SaveMeta implements \SplObserver, ContextAwareInterface
{
	/** @type string */
	private $context = 'save_post_{type}';

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
exit( var_dump( $subject, $data, $_POST ) );
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