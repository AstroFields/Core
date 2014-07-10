<?php

namespace WCM\AstroFields\Settings\Commands;

use WCM\AstroFields\Security\Commands\SanitizeString as Base;

class SanitizeString extends Base
{
	protected $context = 'sanitize_option_{key}';
}