<?php

namespace WCM\AstroFields\Core\Filters;

use WCM\AstroFields\Core\Observers\ViewAwareInterface;

class CommandFilterIterator extends \FilterIterator
{
	public function accept()
	{
		$this->current() instanceof ViewAwareInterface
			AND $this->getInnerIterator()->detach( $this->current() );

		return ! $this->current() instanceof ViewAwareInterface;
	}
}