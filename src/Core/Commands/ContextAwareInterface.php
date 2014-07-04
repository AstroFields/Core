<?php

namespace WCM\AstroFields\Core\Commands;

interface ContextAwareInterface
{
	public function setContext( $context );

	public function getContext();
}