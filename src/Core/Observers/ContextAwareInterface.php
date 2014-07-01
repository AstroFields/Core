<?php

namespace WCM\AstroFields\Core\Observers;

interface ContextAwareInterface
{
	public function setContext( $context );

	public function getContext();
}