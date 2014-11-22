<?php

namespace WCM\AstroFields\Core\Receivers;

/**
 * Interface DataProviderInterface
 * This is the basic recipe for a Receiver that has data attached.
 * Data can be anything from an ID to something much more meta.
 * @package WCM\AstroFields\Core\Receivers
 */
interface DataProviderInterface
{
	public function setData( Array $data );
}