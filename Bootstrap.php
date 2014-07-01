<?php

namespace WCM;

/**
 * Plugin Name: (WCM) AstroFields
 * Description: A PHP Pattern Library for Fields
 */

use WCM\AstroFields\Core\Mediators\Field;
use WCM\AstroFields\Core\Commands\InputField as InputFieldCmd;
use WCM\AstroFields\Core\Mediators\MetaBox;
use WCM\AstroFields\PostMeta\Observers\InputField;

use WCM\AstroFields\PostMeta\Receivers\PostMetaValue;
use WCM\AstroFields\Core\Views\InputField as InputFieldView;
use WCM\AstroFields\Core\Templates\InputField as InputFieldTmpl;

// Drop in Composer autoloader
require_once plugin_dir_path( __FILE__ )."vendor/autoload.php";


add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	$inputField = new InputField;
	$inputField
#		->setContext( 'edit_form_advanced' )
		->setProvider( new PostMetaValue )
		->setTemplate( new InputFieldTmpl );

	$field = new Field( 'wcm_test', array(
		'post',
	) );
	$field->attach( $inputField );

	$metabox = new MetaBox( 'wcm_meta_box', 'WCM Meta Box' );
	$metabox->attach( $field );
} );