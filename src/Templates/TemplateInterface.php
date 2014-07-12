<?php

namespace WCM\AstroFields\Core\Templates;

interface TemplateInterface
{
	/**
	 * Attach data for the template
	 * @param mixed $data
	 */
	public function attach( $data );

	/**
	 * Returns the final MarkUp
	 * @return string
	 */
	public function display();
}