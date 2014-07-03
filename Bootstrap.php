<?php

namespace WCM;

/**
 * Plugin Name: (WCM) AstroFields
 * Description: A PHP Pattern Library for Fields
 */

use WCM\AstroFields\Core\Mediators\Field;
use WCM\AstroFields\Core\Mediators\MetaBox;

use WCM\AstroFields\Core\Templates\InputField as InputFieldTmpl;

use WCM\AstroFields\Security\Observers\SanitizeString;

use WCM\AstroFields\PostMeta\Observers\InputField;
use WCM\AstroFields\PostMeta\Observers\SaveMeta;
use WCM\AstroFields\PostMeta\Receivers\PostMetaValue;

// Drop in Composer autoloader
require_once plugin_dir_path( __FILE__ )."vendor/autoload.php";


add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$inputFieldView = new InputField;
	$inputFieldView
		->setContext( '' )
#		->setContext( 'edit_form_advanced' )
		->setProvider( new PostMetaValue )
		->setTemplate( new InputFieldTmpl );

	$sanitizeString = new SanitizeString;
	$saveMeta = new SaveMeta;

	// Entity: Field
	$field = new Field( 'wcm_test', array(
		'post',
		'page',
	) );
	// Attach Commands
	$field
		->attach( $inputFieldView )
		->attach( $saveMeta )
		->attach( $sanitizeString );

	// MetaBox
	$meta_box = new MetaBox( 'wcm_meta_box', 'WCM Meta Box', array(
		'post',
	) );
	// Attach Entities
	$meta_box->attach( $field );
} );