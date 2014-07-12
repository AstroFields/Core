<?php

namespace WCM\AstroFields\Core\Receivers;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;

interface LabelInterface extends DataProviderInterface
{
	/**
	 * Retrieve the value for the `for=""` attribute
	 * to assign a label to a field
	 * @return string
	 */
	public function getKey();

	/**
	 * Retrieve the value for the tag value
	 * @return string
	 */
	public function getLabel();
}