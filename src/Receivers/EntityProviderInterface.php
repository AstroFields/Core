<?php

namespace WCM\AstroFields\Core\Receivers;

interface EntityProviderInterface extends DataReceiverInterface
{
	public function getKey();

	public function getValue();
}