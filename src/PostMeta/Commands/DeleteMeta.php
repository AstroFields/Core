<?php

namespace WCM\AstroFields\PostMeta\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class DeleteMeta
	implements \SplObserver,
			   ContextAwareInterface
{
	/** @type string */
	private $context = 'save_post_{type}';

	/** @type Array */
	private $data;

	/** @type int */
	private $postID;

	/** @type \WP_Post */
	private $post;

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->data   = $data;
		$this->postID = $data['args'][0];
		$this->post   = $data['args'][1];

		$updated = empty( $_POST[ $data['key'] ] )
			? $this->delete()
			: false;
	}

	public function setContext( $context )
	{
		$this->context = $context;

		return $this;
	}

	public function getContext()
	{
		return $this->context;
	}

	public function delete()
	{
		return delete_post_meta(
			$this->postID,
			$this->data['key']
		);
	}
}