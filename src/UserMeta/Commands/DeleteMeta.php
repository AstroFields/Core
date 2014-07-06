<?php

namespace WCM\AstroFields\UserMeta\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class DeleteMeta
	implements \SplObserver,
			   ContextAwareInterface
{
	/** @type string */
	private $context = 'profile_update';

	/** @type Array */
	private $data;

	/** @type int */
	private $userID;

	/** @type \WP_Post */
	private $post;

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->data   = $data;
		$this->userID = $data['args'][0];
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
		return delete_user_meta(
			$this->userID,
			$this->data['key']
		);
	}
}