<?php

namespace WCM\AstroFields\Core\Views;

use WCM\AstroFields\Core\Receivers\EntityProviderInterface;

interface DataAwareInterface
{
	public function setData( EntityProviderInterface $data );
}