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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_lotto/assets/css/lotto.css');
$document->addStyleSheet(JUri::root() . 'media/com_lotto/css/list.css');

$user      = JFactory::getUser();

$userId    = $user->get('id');



$canOrder  = $user->authorise('core.edit.state', 'com_lotto');




?>

<form action="<?php echo JRoute::_('index.php?option=com_lotto&view=freekassapayments'); ?>" method="post"
      name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>



			<div class="clearfix"></div>
			<table class="table table-striped" id="userList">
				<thead>
				<tr>
				<th class='left'>
				<?php echo JText::_( 'COM_LOTTO_MERCHAND_ID'); ?>
				</th>
				<th class='left'>
				<?php echo JText::_( 'COM_LOTTO_MERCHAND_KEY'); ?>
				</th>




				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php // echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php


                foreach ($this->items as $i => $item) :

					?>
					<tr class="row<?php echo $i % 2; ?>">



						<td>

					        <?php echo $item->merchant_id; ?>
			            	</td>
                        <td>

                            <?php echo $item->merchant_key; ?>
                        </td>

                       	</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

            <input type="hidden" name="task" value=""/>
            <input type="hidden" name="boxchecked" value="0"/>

            <?php echo JHtml::_('form.token'); ?>


		</div>
</form>

        <script>
            window.toggleField = function (id, task, field) {

                var f = document.adminForm, i = 0, cbx, cb = f[ id ];

                if (!cb) return false;

                while (true) {
                    cbx = f[ 'cb' + i ];

                    if (!cbx) break;

                    cbx.checked = false;
                    i++;
                }

                var inputField   = document.createElement('input');

                inputField.type  = 'hidden';
                inputField.name  = 'field';
                inputField.value = field;
                f.appendChild(inputField);

                cb.checked = true;
                f.boxchecked.value = 1;
                window.submitform(task);

                return false;
            };
