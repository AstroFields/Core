<?php

namespace WCM\AstroFields\Core\Commands;

interface ContextAwareInterface
{
	/**
	 * @param string $context
	 * @return mixed
	 */
	public function setContext( $context );

	/**
	 * @return array
	 */
	public function getContext();
}