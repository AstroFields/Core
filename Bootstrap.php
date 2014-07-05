<?php

namespace WCM;

/**
 * Plugin Name: (WCM) AstroFields
 * Description: A PHP Pattern Library for Fields
 */

use WCM\AstroFields\Core\Mediators\Entity;
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
use WCM\AstroFields\Security\Commands\SanitizeMail;

use WCM\AstroFields\PostMeta\Commands\SaveMeta;
use WCM\AstroFields\PostMeta\Commands\DeleteMeta;
use WCM\AstroFields\PostMeta\Receivers\PostMetaValue;

// Drop in Composer autoloader
require_once plugin_dir_path( __FILE__ )."vendor/autoload.php";


add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$mail_view = new FieldCmd;
	$mail_view
		->setProvider( new PostMetaValue )
		->setTemplate( new EmailFieldTmpl );

	// Entity: Field
	$mail_field = new Entity( 'wcm_test', array(
		'post',
		'page',
	) );
	// Attach Commands
	$mail_field
		->attach( $mail_view, array(
			'attributes' => array(
				'size'     => 40,
				'class'    => 'foo bar baz',
			),
		) )
		->attach( new SaveMeta )
		->attach( new DeleteMeta )
		->attach( new SanitizeMail );

	// Commands
	$select_view = new FieldCmd;
	$select_view
		->setProvider( new PostMetaValue )
		->setTemplate( new SelectFieldTmpl );

	// Entity: Field
	$select_field = new Entity( 'wcm_select', array(
		'post',
	) );
	// Attach Commands
	$select_field
		->attach( $select_view, array(
			'attributes' => array(
				'size'     => 40,
				'class'    => 'foo bar baz',
			),
			'options' => array(
				''        => '-- select --',
				'bar'     => 'Bar',
				'foo'     => 'Foo',
				'baz'     => 'Baz',
				'dragons' => 'Dragons',
			),
		) )
		->attach( new DeleteMeta )
		->attach( new SaveMeta )
		->attach( new SanitizeString );

	// MetaBox
	$meta_box = new MetaBox( 'wcm_meta_box', 'WCM Meta Box', array(
		'post',
		'page',
	) );
	// Attach Entities
	$meta_box
		->attach( $select_field, 2 )
		->attach( $mail_field, 5 );
} );