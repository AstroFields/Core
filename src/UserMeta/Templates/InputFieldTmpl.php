<?php

namespace WCM\AstroFields\UserMeta\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface,
	WCM\AstroFields\Core\Templates\PrintableInterface,
	WCM\AstroFields\Core\Receivers\FieldInterface,
	WCM\AstroFields\Core\Receivers\AttributeAwareInterface;

use WCM\AstroFields\Standards\Templates\InputFieldTmpl as BaseTmpl;

class InputFieldTmpl
	extends BaseTmpl
	implements TemplateInterface,
			   PrintableInterface
{
	/**
	 * @return string
	 */
	public function display()
	{
		$html  = '<table class="form-table"><tbody><tr>';
		$html .= sprintf(
			'<th scope="row"><label for="%s">Foo</label></th>',
			$this->data->getKey()
		);
		$html .= sprintf(
			'<td>%s</td>',
			parent::getMarkUp()
		);
		$html .= '</tr></tbody></table>';

		return $html;
	}
}