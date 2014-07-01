<?php

namespace WCM\AstroFields\Core\Receivers;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;

interface LabelInterface extends DataProviderInterface
{
	public function getLabel();
}