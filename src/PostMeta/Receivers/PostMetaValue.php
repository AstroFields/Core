<?php

namespace WCM\AstroFields\PostMeta\Receivers;

use WCM\AstroFields\Core\Receivers\FieldInterface;
use WCM\AstroFields\Core\Receivers\AttributeAwareInterface;
use WCM\AstroFields\Core\Receivers\OptionAwareInterface;

class PostMetaValue
	implements FieldInterface,
			   AttributeAwareInterface,
			   OptionAwareInterface
{
	/** @type Array */
	private $data;

	/**
	 * Set the data to deliver to the template
	 * @param array $data
	 */
	public function setData( Array $data )
	{
		$this->data = $data;
	}

	/**
	 * Retrieve the key used in `name` and (optional) the `id`
	 * @return string
	 */
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

	/**
	 * Retrieve (optional) `attributes`
	 * If the `value` stays empty, only the `key` gets assigned
	 * Use this for i.e. `required`
	 * @return string
	 */
	public function getAttributes()
	{
		if ( ! isset( $this->data['attributes'] ) )
			return '';

		$result = '';
		foreach ( $this->data['attributes'] as $key => $val )
		{
			$result .= " {$key}";
			! empty( $val ) AND $result .= "='{$val}'";
		}

		return $result;
	}

	/**
	 * Retrieve (optional) `options`
	 * @return array
	 */
	public function getOptions()
	{
		if ( ! isset( $this->data['options'] ) )
			return array();

		return $this->data['options'];
	}
}