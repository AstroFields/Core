<?php

namespace WCM;

/**
 * Plugin Name: (WCM) AstroFields
 * Description: A PHP Pattern Library for Fields
 */

use WCM\AstroFields\Core\Mediators\Entity;

use WCM\AstroFields\MetaBox\Commands\MetaBox as MetaBoxCmd;
use WCM\AstroFields\MetaBox\Receivers\MetaBox as MetaBoxProvider;
use WCM\AstroFields\MetaBox\Templates\Table;

use WCM\AstroFields\Core\Commands\ViewCmd;

use WCM\AstroFields\MetaBox\Views\MetaBoxView;
use WCM\AstroFields\Settings\Commands\DeleteOption;
use WCM\AstroFields\Settings\Commands\SaveOption;
use WCM\AstroFields\Standards\Templates\InputFieldTmpl;
use WCM\AstroFields\Standards\Templates\PasswordFieldTmpl;
use WCM\AstroFields\Standards\Templates\RadioFieldTmpl;
use WCM\AstroFields\Standards\Templates\SelectFieldTmpl;
use WCM\AstroFields\Standards\Templates\CheckboxListTmpl;
use WCM\AstroFields\Standards\Templates\CheckboxFieldTmpl;
use WCM\AstroFields\HTML5\Templates\EmailFieldTmpl;

use WCM\AstroFields\UserMeta\Templates\InputFieldTmpl as InputFieldTmplUser;

use WCM\AstroFields\Security\Commands\SanitizeString;
use WCM\AstroFields\Security\Commands\SanitizeMail;

use WCM\AstroFields\PostMeta\Commands\SaveMeta;
use WCM\AstroFields\PostMeta\Commands\DeleteMeta;
use WCM\AstroFields\PostMeta\Receivers\PostMetaValue;
use WCM\AstroFields\Standards\Templates\TextareaFieldTmpl;

use WCM\AstroFields\UserMeta\Commands\SaveMeta as SaveUserMeta;
use WCM\AstroFields\UserMeta\Commands\DeleteMeta as DeleteUserMeta;
use WCM\AstroFields\UserMeta\Receivers\UserMetaValue;

use WCM\AstroFields\Settings\Commands\SettingsSection;
use WCM\AstroFields\Settings\Receivers\OptionValue;


// Drop in Composer autoloader
require_once plugin_dir_path( __FILE__ )."vendor/autoload.php";


### POST META
add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$mail_view = new ViewCmd;
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
	$select_view = new ViewCmd;
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

	// Commands
	$textarea_view = new ViewCmd;
	$textarea_view
		->setProvider( new PostMetaValue )
		->setTemplate( new TextareaFieldTmpl );

	// Entity: Field
	$textarea_field = new Entity( 'wcm_textarea', array(
		'post',
	) );
	// Attach Commands
	$textarea_field
		->attach( $textarea_view, array(
			'attributes' => array(
				'class' => 'attachmentlinks',
				'rows'  => 5,
				'cols'  => 40,
			),
		) )
		->attach( new DeleteMeta )
		->attach( new SaveMeta )
		->attach( new SanitizeString );

	// Command
	$meta_box_cmd = new MetaBoxCmd( 'Test Box' );
	$meta_box_cmd
		->attach( $select_field, 2 )
		->attach( $textarea_field, 8 )
		->attach( $mail_field, 5 )
		->setTemplate( new Table );
	// Entity: MetaBox
	$meta_box = new Entity( 'wcm_meta_box', array(
		'post',
		'page',
	) );
	$meta_box->attach( $meta_box_cmd );
} );


### USER META
add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$input_view = new ViewCmd;
	$input_view
		->setContext( 'edit_user_profile' )
		->setProvider( new UserMetaValue )
		->setTemplate( new InputFieldTmplUser );

	// Entity: Field
	$input_field = new Entity( 'wcm_input_user', array(
		'post',
	) );
	// Attach Commands
	$input_field
		->attach( $input_view, array(
			'attributes' => array(
				'class' => 'regular-text',
			),
		) )
		->attach( new DeleteUserMeta )
		->attach( new SaveUserMeta )
		->attach( new SanitizeString );
} );


### SETTINGS SECTION
add_action( 'wp_loaded', function()
{
	if ( ! is_admin() )
		return;

	// Commands
	$section = new SettingsSection( 'Hello', 'foo' );
	$input_view = new ViewCmd;
	$input_view
		->setContext( 'admin_head-options-{type}.php' )
		->setProvider( new OptionValue )
		->setTemplate( new InputFieldTmplUser );

	// Entity: Field
	$input_field = new Entity( 'wcm_settings_section', array(
		'general',
		'permalink',
	) );
	// Attach Commands
	$input_field
		->attach( $input_view, array(
			'attributes' => array(
				'class' => 'regular-text',
			),
		) )
		->attach( new DeleteOption )
		->attach( new SaveOption )
		->attach( new SanitizeString );
} );