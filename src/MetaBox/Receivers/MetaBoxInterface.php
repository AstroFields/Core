<?php

namespace WCM\AstroFields\MetaBox\Receivers;

interface MetaBoxInterface
{
	/**
	 * Return the MetaBox handle
	 * @return string
	 */
	public function getKey();

	/**
	 * Return the post object passed as first argument to the meta box callback
	 * @return \WP_Post
	 */
	public function getPost();

	/**
	 * Return the array of arguments passed as second argument to the meta box callback
	 * @return array
	 */
	public function getArgs();

	/**
	 * Return the Stack that holds all assigned entities
	 * @return \SplPriorityqueue
	 */
	public function getEntities();
}