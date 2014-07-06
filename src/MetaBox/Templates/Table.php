<?php

namespace WCM\AstroFields\MetaBox\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface;
use WCM\AstroFields\Core\Receivers\DataProviderInterface;
use WCM\Meta\Commands\MetaBoxInterface;

class Table implements TemplateInterface
{
	/** @type \SplPriorityQueue */
	private $entities;

	/**
	 * Attach the entities
	 * @param \SplPriorityQueue $entities
	 * @return $this
	 */
	public function attach( $entities )
	{
		$this->entities = $entities;

		return $this;
	}

	/**
	 * Render the Entities
	 * @return string
	 */
	public function display()
	{
		echo '<table class="wp-list-table  widefat"><tbody>';
		foreach ( $this->entities as $entity )
		{
			$class = 0 === $this->entities->key() %2 ? ' class="alternate"' : '';
			echo "<tr{$class}>";
				echo '<td>Foo</td>';
				echo '<td>';
					$this->entities
						->current()
						->notify();
				echo '</td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}
}