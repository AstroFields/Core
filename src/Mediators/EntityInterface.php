<?php

namespace WCM\AstroFields\Core\Mediators;

use WCM\AstroFields\Core\Commands\ContextAwareInterface;

interface EntityInterface
{
	/**
	 * @param $proxy
	 * @return mixed
	 */
	public function setProxy( $proxy );

	/**
	 * @param ContextAwareInterface $command
	 * @param array            $data
	 * @return array
	 */
	public function parseContext( ContextAwareInterface $command, Array $data );

	/**
	 * @param ContextAwareInterface $command
	 * @param array            $data
	 */
	public function notify( ContextAwareInterface $command, Array $data = array() );
}