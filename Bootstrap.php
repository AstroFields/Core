<?php

namespace WCM;

/**
 * Plugin Name: (WCM) AstroFields
 * Description: A PHP Pattern Library for Fields
 */

use WCM\AstroFields\Core\Mediators\Field;
use WCM\AstroFields\Core\Mediators\MetaBox;

use WCM\AstroFields\Core\Commands\FieldCmd;

use WCM\AstroFields\Standards\Templates\InputFieldTmpl;
use WCM\AstroFields\Standards\Templates\PasswordFieldTmpl;
use WCM\AstroFields\Standards\Templates\RadioFieldTmpl;
use WCM\AstroFields\Standards\Templates\SelectFieldTmpl;
use WCM\AstroFields\Standards\Templates\CheckboxListTmpl;
use WCM\AstroFields\Standards\Templates\CheckboxFieldTmpl;
use WCM\AstroFields\HTML5\Templates\EmailFieldTmpl;

use WCM\AstroFields\Security\Commands\SanitizeString;

use WCM\AstroFields\PostMeta\Commands\SaveMeta;
use WCM\AstroFields\PostMeta\Receivers\PostMetaValue;

// Drop in Composer autoloader
require_once plugin_dir_path( __FILE__ )."vendor/autoload.php";


add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$inputField = new FieldCmd;
	$inputField
#		->setContext( 'edit_form_advanced' )
		->setProvider( new PostMetaValue )
		->setTemplate( new EmailFieldTmpl );

	// Entity: Field
	$field = new Field( 'wcm_test', array(
		'post',
		'page',
	) );
	// Attach Commands
	$field
		->attach( $inputField, array(
			'attributes' => array(
				'size'     => 40,
				'class'    => 'foo bar baz',
				'required' => '',
			),
			'options' => array(
				'bar'     => 'Bar',
				'foo'     => 'Foo',
				'baz'     => 'Baz',
				'dragons' => 'Dragons',
			),
		) )
		->attach( new SaveMeta )
		->attach( new SanitizeString );

	// MetaBox
	$meta_box = new MetaBox( 'wcm_meta_box', 'WCM Meta Box', array(
		'post',
	) );
	// Attach Entities
	$meta_box->attach( $field );
} );