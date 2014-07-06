<?php

namespace WCM\AstroFields\MetaBox\Receivers;

use WCM\AstroFields\Core\Receivers\DataProviderInterface;

class MetaBox implements DataProviderInterface
{
	/** @type array */
	private $data;

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
	 * Return the post object passed as first argument to the meta box callback
	 * @return \WP_Post
	 */
	public function getPost()
	{
		return $this->data['post'];
	}

	/**
	 * Return the array of arguments passed as second argument to the meta box callback
	 * @return array
	 */
	public function getArgs()
	{
		return $this->data['args'];
	}

	/**
	 * Return the Stack that holds all assigned entities
	 * @return \SplPriorityqueue
	 */
	public function getEntities()
	{
		return $this->data['entities'];
	}
}