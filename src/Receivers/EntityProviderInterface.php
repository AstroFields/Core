<?php

namespace WCM\AstroFields\Core\Receivers;

interface EntityProviderInterface extends DataProviderInterface
{
	public function getKey();

	public function getValue();
}