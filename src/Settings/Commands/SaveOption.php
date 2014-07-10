<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

class SaveOption
	implements \SplObserver,
			   ContextAwareInterface
{
	/** @type string */
	private $context = 'pre_set_transient_settings_errors';

	/** @type Array */
	private $data;

	/**
	 * @param \SplSubject $subject
	 * @param array       $data
	 */
	public function update( \SplSubject $subject, Array $data = null )
	{
		$this->data   = $data;

		if (
			! isset( $_POST[ $data['key'] ] )
			OR empty( $_POST[ $data['key'] ] )
		)
			return;

#		$updated = $this->save();
#exit( var_dump( $updated ) );
#		$notice  = $this->check( $updated );
		# @TODO Do something with the notice
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

	public function save()
	{
		return update_option(
			$this->data['key'],
			$_POST[ $this->data['key'] ]
		);
	}

	public function check( $updated )
	{
		$notice = '';
		/** @var \WP_Error $updated */
		if ( is_wp_error( $updated ) )
		{
			$notice = sprintf(
				'%s: %s',
				$updated->get_error_code(),
				$updated->get_error_message()
			);
		}
		elseif ( is_int( $updated ) )
		{
			esc_url( add_query_arg( 'message', 5, get_permalink( $this->postID ) ) );
			$notice = "New value added for: {$this->data['key']}";
		}
		elseif ( ! $updated )
		{
			esc_url( add_query_arg( 'message', 6, get_permalink( $this->postID ) ) );
			$notice = 'Post meta not updated';
		}
		else
		{
			esc_url( add_query_arg( 'message', 7, get_permalink( $this->postID ) ) );
			$notice = 'Post Meta updated';
		}

		return $notice;
	}
}