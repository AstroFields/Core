<?php

namespace WCM\AstroFields\Core\Receivers;

/**
 * Interface OptionAwareInterface
 * The basic interface something that comes with options.
 * Common examples are Radio Buttons, Select form fields
 * Checkbox sets or lists.
 * Use this as indicator/recipe for key/value a storage.
 * @package WCM\AstroFields\Core\Receivers
 */
interface OptionAwareInterface extends EntityProviderInterface
{
	public function getOptions();
}