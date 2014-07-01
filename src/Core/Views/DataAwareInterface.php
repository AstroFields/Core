<?php

namespace WCM\AstroFields\Core\Views;

use WCM\AstroFields\Core\Receivers\FieldInterface;

interface DataAwareInterface
{
	public function setData( FieldInterface $data );
}