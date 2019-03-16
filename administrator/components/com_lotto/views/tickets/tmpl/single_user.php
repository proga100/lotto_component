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

jimport('joomla.application.component.model');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_lotto/models', 'LottoModel');
$it = JModelLegacy::getInstance( 'User', 'LottoModel' );


// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_lotto/assets/css/lotto.css');
$document->addStyleSheet(JUri::root() . 'media/com_lotto/css/list.css');

$user      = JFactory::getUser();

$userId    = $user->get('id');


$listOrder = $this->state->get('list.ordering');
$listDirn  = $this->state->get('list.direction');
$canOrder  = $user->authorise('core.edit.state', 'com_lotto');
$saveOrder = $listOrder == 'a.`ordering`';


$sortFields = $this->getSortFields();
?>

<form action="<?php echo JRoute::_('index.php?option=com_lotto&view=tickets&layout=single_user'); ?>" method="post"
	  name="adminForm" id="adminForm">
	<?php if (!empty($this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
			<?php endif; ?>
           <h3> <?php echo JText::_('COM_LOTTO_NICKNAME').": ". $this->escape(JFactory::getUser($_REQUEST['user_id'])->get('name')); ?></h3>
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
						<!-- <input type="checkbox" name="checkall-toggle" value=""
							   title="<?php  echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/> !-->
					</th>
					<?php if (isset($this->items[0]->state)): ?>
						<th width="1%" class="nowrap center">
								<?php // echo JHtml::_('searchtools.sort', 'JSTATUS', 'a.`state`', $listDirn, $listOrder); ?>
                    </th>
					<?php endif; ?>

                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_USERS_TICKETS_TIRAJ', 'a.`tickets_tiraj_numbers`', $listDirn, $listOrder); ?>
                    </th>

				<th class='left'>


				<?php  echo JHtml::_('searchtools.sort',  'COM_LOTTO_TICKETS_ID', 'a.`tickets_id`', $listDirn, $listOrder); ?>
				</th>


				<th class='left'>
				<?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_NUMBERS_BIDS', 'a.`numbers`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JText::_('COM_LOTTO_WINS'); ?>
                    </th>
                    <!--
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_INVOICE_ID', 'a.`invoice_id`', $listDirn, $listOrder); ?>
                    </th>
                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_CREATED_DATE', 'a.`created_data`', $listDirn, $listOrder); ?>
                    </th>

                    !-->

					
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
				<?php


                foreach ($this->items as $i => $item) :
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


							<?php // echo JHtml::_('grid.id', $i, $item->tickets_id); ?>
						</td>

                        <td>

                            <?php echo $item->tickets_tiraj_numbers; ?>
                        </td>


                        <td >
                            <?php echo $item->tickets_id; ?>
                        </td>



                        <td>
                            <?php echo $item->numbers; ?>

				        </td>

                        <td>
                            0

                        </td>
                        <!--
                        <td> <?php echo $item->invoice_id; ?></td>
                        <td> <?php echo $item->created_data; ?></td>
                        !-->

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