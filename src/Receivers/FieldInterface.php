<?php

namespace WCM\AstroFields\Core\Receivers;

interface FieldInterface extends DataProviderInterface
{
	public function getKey();

	public function getValue();
}