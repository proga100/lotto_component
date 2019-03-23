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


$tiraje_info = JModelLegacy::getInstance('tiraje', 'LottoModel');


?>

<form action="<?php echo JRoute::_('index.php?option=com_lotto&view=currenttiraje'); ?>" method="post"
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
				<?php echo JText::_( 'COM_LOTTO_ID'); ?>
				</th>
				<th class='left'>
				<?php echo JText::_( 'COM_LOTTO_USERS_TICKETS_TIRAJ'); ?>
				</th>
				<th class='left'>
				<?php echo JText::_( 'COM_LOTTO_WINNING_NUMBERS'); ?>
				</th>



                    <th class='left'>
                        <?php echo JText::_('COM_LOTTO_WINNING_ADD_NUMBERS'); ?>
                    </th>

                    <th class='left'>
                        <?php echo JText::_('COM_LOTTO_WINNING_PRIZE'); ?>
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
					//$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_lotto');
					$canEdit    = $user->authorise('core.edit', 'com_lotto');
					$canCheckin = $user->authorise('core.manage', 'com_lotto');
					$canChange  = $user->authorise('core.edit.state', 'com_lotto');
					?>
					<tr class="row<?php echo $i % 2; ?>">

                        <td >
                            <?php echo $item->id; ?>
                        </td>

						<td>

					        <?php // echo $item->tiraje_number;


                            $tirajeinf =  $tiraje_info->getTirajeInfobyID($item->tiraje_number);
                            print_r ( $tirajeinf[0]->Tiraje_number);



                            ?>
			            	</td>
                        <td>

                            <?php echo $item->main_winning_numbers; ?>
                        </td>
                        <td>

                            <?php echo $item->additional_winning_numbers; ?>
                        </td>

                        <td>

                            <?php

                            $win_pr= json_decode($item->winning_prize);
							
						

                            echo '<div class="prize_b_1">'.JText::_('COM_LOTTO_WINNING_NUMBER',true) .'</div>'.
                                '<div class="prize_b_1">'.JText::_('COM_LOTTO_WINNING_ADD_NUMBER',true).'</div>'.
                                '<div class="prize_b_1">'.JText::_('COM_LOTTO_WINNING_PRIZE',true).'</div><div style="clear:both;"></div><br/>';

                            foreach ($win_pr as $prize){
                                if(empty($prize[1])) $prize[1] = 0;

                                echo '<div class="prize_b_1">'. $prize[0].'</div>'.'<div class="prize_b_1">'.$prize[1].'</div>'.
                                '<div class="prize_b_1">'.$prize[2].'</div>';

                            }
                            echo '<div style="clear:both;" ></div>';
                          ?>
                        </td>
                       	</tr>
				<?php endforeach; ?>
				</tbody>
			</table>

			<input type="hidden" name="task" value=""/>
			<input type="hidden" name="boxchecked" value="0"/>
            <input type="hidden" name="list[fullorder]" value="<?php echo $listOrder; ?> <?php echo $listDirn; ?>"/>
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
</script>