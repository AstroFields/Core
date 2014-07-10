<?php

namespace WCM\AstroFields\Settings\Templates;

use WCM\AstroFields\Standards\Templates\InputFieldTmpl;

class InputSettingsTmpl extends InputFieldTmpl
{
	/**
	 * @return string
	 */
	public function display()
	{
		return sprintf(
			'<td>%s</td>',
			$this->getMarkUp()
		);
	}
}