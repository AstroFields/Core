<?php

namespace WCM\AstroFields\PostMeta\Receivers;

use WCM\AstroFields\Core\Receivers\FieldInterface;

class PostMetaValue implements FieldInterface
{
	/** @type Array */
	private $data;

	public function setData( Array $data )
	{
		$this->data = $data;
	}

	public function getKey()
	{
		return $this->data['key'];
	}

	/**
	 * Retrieve the meta value
	 * @return string
	 */
	public function getValue()
	{
		return get_post_meta(
			get_the_ID(),
			$this->data['key'],
			true
		);
	}
}