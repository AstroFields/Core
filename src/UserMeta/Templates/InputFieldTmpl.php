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
		return <<<EOF
<table class="form-table">
	<tbody>
		<tr>
			<th scope="row">
				<label for="{$this->data->getKey()}">Foo</label>
			</th>
			<td>{$this->getMarkUp()}</td>
		</tr>
	</tbody>
</table>
EOF;
	}
}