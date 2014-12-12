<?php

namespace WCM\AstroFields\Core\Providers;

/**
 * Interface AttributeAwareInterface
 * Use this to transform a key/value storage to a string.
 * The common use case would attributes for a HTML tag.
 * For e.g.: foo="bar" baz="dragons"
 * @package WCM\AstroFields\Core\Providers
 */
interface AttributeAwareInterface extends EntityProviderInterface
{
	public function getAttributes();
}