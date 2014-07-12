<?php

namespace WCM\AstroFields\Core\Views;

use WCM\AstroFields\Core\Receivers\LabelInterface;

interface LabelAwareInterface
{
	public function setData( LabelInterface $data );
}