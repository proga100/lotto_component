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
$tiraje_info = JModelLegacy::getInstance('tiraje', 'LottoModel');


$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_lotto');
$saveOrder = $listOrder == 'a.`ordering`';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_lotto&task=users.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'userList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_lotto&view=users'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>

            <?php // echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

			<div class="clearfix"></div>
			<table class="table table-striped" id="userList">
				<thead>
				<tr>
					<?php if (isset($this->items[0]->ordering)): ?>
						<th width="1%" class="nowrap center hidden-phone">
                            <?php echo JHtml::_('searchtools.sort', '', 'a.`ordering`', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
                        </th>
					<?php endif; ?>
					<th width="1%" class="hidden-phone">
						<input type="checkbox" name="checkall-toggle" value=""
							   title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
					</th>

				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_USERS_NAME', 'a.`name`', $listDirn, $listOrder); ?>
				</th>

                    <th class='left' colspan="4">
                        <?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_USERS_TICKETS_TIRAJ', 'a.`tickets_tiraj_numbers`', $listDirn, $listOrder); ?>
                    </th>




                    <th class='left'>

                    </th>

				<th class='left'>

				</th>



					
				</tr>
				</thead>
				<tfoot>
				<tr>
					<td colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
						<?php echo $this->pagination->getListFooter(); ?>
					</td>
				</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) :
                    $db =& JFactory::getDBO();
                    $query = "SELECT * FROM #__lotto_tickets WHERE user_id=".(int) $item->id ;
                    if ($_REQUEST['tiraje_id']){
                        $query .= "  AND   tickets_tiraj_numbers = ".$_REQUEST['tiraje_id'];

                    }

                    $db->setQuery($query);

                    $rows = $db->loadObjectList();
                    if(! $rows) continue;

					$ordering   = ($listOrder == 'a.ordering');
					$canCreate  = $user->authorise('core.create', 'com_lotto');
					$canEdit    = $user->authorise('core.edit', 'com_lotto');
					$canCheckin = $user->authorise('core.manage', 'com_lotto');
					$canChange  = $user->authorise('core.edit.state', 'com_lotto');
					?>
					<tr class="row<?php echo $i % 2; ?>">

						<?php if (isset($this->items[0]->ordering)) : ?>
							<td class="order nowrap center hidden-phone">
								<?php if ($canChange) :
									$disableClassName = '';
									$disabledLabel    = '';

									if (!$saveOrder) :
										$disabledLabel    = JText::_('JORDERINGDISABLED');
										$disableClassName = 'inactive tip-top';
									endif; ?>
									<span class="sortable-handler hasTooltip <?php echo $disableClassName ?>"
										  title="<?php echo $disabledLabel ?>">
							<i class="icon-menu"></i>
						</span>
									<input type="text" style="display:none" name="order[]" size="5"
										   value="<?php echo $item->ordering; ?>" class="width-20 text-area-order "/>
								<?php else : ?>
									<span class="sortable-handler inactive">
							<i class="icon-menu"></i>
						</span>
								<?php endif; ?>
							</td>
						<?php endif; ?>
						<td class="hidden-phone">
							<?php echo JHtml::_('grid.id', $i, $item->id); ?>
						</td>
						  <td>
				<?php if (isset($item->checked_out) && $item->checked_out && ($canEdit || $canChange)) : ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $item->uEditor, $item->checked_out_time, 'users.', $canCheckin); ?>
				<?php endif; ?>
				<?php if ($canEdit) : ?>
					<a href="<?php echo JRoute::_('index.php?option=com_lotto&view=user&layout=history&id='.(int) $item->id); ?>">
					<?php echo $this->escape($item->name); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->name); ?>
				<?php endif; ?>

				</td>

                        <td colspan="5" class="col_4 sub">

                            <?php

                            $db =& JFactory::getDBO();
                            $query = "SELECT * FROM #__lotto_tickets WHERE user_id=".(int) $item->id ;
                            if ($_REQUEST['tiraje_id']){
                            $query .= "  AND   tickets_tiraj_numbers = ".$_REQUEST['tiraje_id'];

                            }


                           $db->setQuery($query);

                          $rows = $db->loadObjectList();

							$win_num = $this->getwinningnumbers($_REQUEST['tiraje_id']);
							
							
							
							$main_win_numbers = $win_num[0]->main_winning_numbers;
							$main_win_numbers = explode(',', $main_win_numbers);
							$add_win_numbers = $win_num[0]->additional_winning_numbers;
							$add_win_numbers= explode(',', $add_win_numbers);
							//	print_r ($main_win_numbers);
								// print_r ($add_win_numbers);
                            $tickets_tir = array();
                            foreach ($rows as $row) {

                                $tickets_tir[$row->tickets_tiraj_numbers][] = $row;

                            }
                         foreach ( $tickets_tir as $key=>$tickets){


                            ?>
                            <table class="t_width">
                                <tr>
                                    <td class="table_td">
                                        <a href="<?php echo JRoute::_('index.php?option=com_lotto&view=tiraje&tiraje_id='.(int) $key); ?>">

                                       <?php
                                      $tirajeinf =  $tiraje_info->getTirajeInfobyID($key);


                                      print_r ( $tirajeinf[0]->Tiraje_number);
                                       ?>
                                            </a>
                                    </td>
                                    <td colspan="2" class="col_4  sub">
                                    <table class="t_width">
                                        <tr>

                                            <th class='left'>
                                                <?php echo JText::_(  'COM_LOTTO_TICKETS_ID'); ?>
                                            </th>


                                            <th class='left'>
                                                <?php echo JText::_( 'COM_LOTTO_NUMBERS'); ?>
                                            </th>

                                            <th class='left'>
                                                <?php echo JText::_( 'COM_LOTTO_NUMBERS_ADDS'); ?>
                                            </th>
                                        </tr>
                                    <?php
                                    $ticket_paid_total_price = 0;
                                    foreach ($tickets as $tik) {

                                        $ticket_paid_total_price +=$tik->price;
                                        ?>


                                    <tr>
                                    <td class="table_td_sub">
                                        <?php  echo $tik->tickets_id; ?>
                                    </td>
                                    <td> 
                                        <?php 

											// echo $tik->numbers; 
										
										$tik_numbers = explode(',', $tik->numbers);
										foreach ($tik_numbers as $tik_num){
											if (in_array($tik_num, $main_win_numbers)){
													echo '<span style="background:green;color:white;margin-left:5px;">'.$tik_num.'</span>'; 
											}else{
												echo '<span style="margin-left:5px;">'.$tik_num.'</span>';  
												
											}
											
											
										}
										
										
										?>
                                    </td>
                                        <td>
                                            <?php  // echo $tik->additional_numbers; 
											
											$tik_additional_numbers= explode(',', $tik->additional_numbers);
										foreach ($tik_additional_numbers as $tik_num){
											if (in_array($tik_num, $add_win_numbers)){
												echo '<span style="background:green;color:white;margin-left:5px;">'.$tik_num.'</span>'; 
											}else{
												echo '<span style="margin-left:5px;">'.$tik_num.'</span>'; 
												
											}
											
											
										}
											
											
											
											?>
                                        </td>
                                    </tr>

                                        <?php } ?>

                                    </table>
                                    <td>



                                    <table>
                                        <tr>
                                            <th class='left'>
                                                <?php echo JText::_('COM_LOTTO_GAME'); ?>
                                            </th>

                                        </tr>
                                        <tr>
                                            <td>
                                                <?php echo $ticket_paid_total_price ?>
                                            </td>
                                        </tr>
                                    </table>
                                    </td>

                                </tr>


                           </table>



                         <?php



                         }

                            ?>


                        </td>

                 <td>

                        <table>
                            <tr>


                                <th class='left'>
                                    <?php echo JText::_('COM_LOTTO_USERS_BALANCE'); ?>
                                </th>

                            </tr>
                            <tr>
                                <td>
                                    <?php echo $item->balance; ?>
                                </td>
                            </tr>
                            </table>


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