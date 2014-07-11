<?php

namespace WCM\AstroFields\PublicForm\Receivers;

use WCM\AstroFields\Core\Receivers\FieldInterface;

class Field implements FieldInterface
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

	public function getValue()
	{
		return isset( $this->data['default'] )
			? $this->data['default']
			: '';
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