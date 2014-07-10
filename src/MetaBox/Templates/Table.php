<?php

namespace WCM\AstroFields\MetaBox\Templates;

use WCM\AstroFields\Core\Templates\TemplateInterface;

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
		?>
		<table class="wp-list-table  widefat">
			<tbody>
			<?php
			foreach ( $this->entities as $entity )
			{
				$class = 0 === $this->entities->key() %2 ?
					' class="alternate"'
					: '';
				?>
				<tr<?php echo $class; ?>>
					<td>Foo</td>
					<td>
						<?php
						$this->entities
							->current()
							->notify();
						?>
					</td>
				</tr>
				<?php
			}
			?>
			</tbody>
		</table>
		<?php
	}
}