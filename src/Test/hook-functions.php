<?php

use \WCM\AstroFields\Core\Test;

if ( ! function_exists( '_wp_filter_build_unique_id' ) )
{
	function _wp_filter_build_unique_id( $tag = null, $function = null, $priority = null )
	{
		return Test\HooksMock::callbackUniqueId( $function );
	}
}

if ( ! function_exists( 'add_filter' ) )
{
	function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 )
	{
		return true;
	}
}

if ( ! function_exists( 'remove_filter' ) )
{
	function remove_filter( $tag, $function_to_remove, $priority = 10 )
	{
		return true;
	}
}