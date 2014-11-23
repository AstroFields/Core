<?php

namespace WCM\AstroFields\Core\Receivers;

/**
 * Interface AttributeAwareInterface
 * Use this to transform a key/value storage to a string.
 * The common use case would attributes for a HTML tag.
 * For e.g.: foo="bar" baz="dragons"
 * @package WCM\AstroFields\Core\Receivers
 */
interface AttributeAwareInterface extends EntityProviderInterface
{
	public function getAttributes();
}