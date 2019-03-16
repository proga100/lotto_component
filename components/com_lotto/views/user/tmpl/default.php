<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Lotto
 * @author     flance ltd <tutyou1972@gmail.com>
 * @copyright  2018 flance LTD
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;


?>

<div class="item_fields">

	<table class="table">
		

		<tr>
			<th><?php echo JText::_('COM_LOTTO_FORM_LBL_USER_ID'); ?></th>
			<td><?php echo $this->item->id; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_LOTTO_FORM_LBL_USER_NAME'); ?></th>
			<td><?php echo $this->item->name; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_LOTTO_FORM_LBL_USER_TICKETS'); ?></th>
			<td><?php echo $this->item->tickets; ?></td>
		</tr>

		<tr>
			<th><?php echo JText::_('COM_LOTTO_FORM_LBL_USER_BALANCE'); ?></th>
			<td><?php echo $this->item->balance; ?></td>
		</tr>

	</table>

</div>

