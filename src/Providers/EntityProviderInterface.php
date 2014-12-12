<?php

namespace WCM\AstroFields\Core\Providers;

interface EntityProviderInterface extends DataProviderInterface
{
	public function getKey();

	public function getValue();
}