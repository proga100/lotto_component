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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user       = JFactory::getUser();
$userId     = $user->get('id');
$listOrder  = $this->state->get('list.ordering');
$listDirn   = $this->state->get('list.direction');
$canCreate  = $user->authorise('core.create', 'com_lotto') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'userform.xml');
$canEdit    = $user->authorise('core.edit', 'com_lotto') && file_exists(JPATH_COMPONENT . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . 'forms' . DIRECTORY_SEPARATOR . 'userform.xml');
$canCheckin = $user->authorise('core.manage', 'com_lotto');
$canChange  = $user->authorise('core.edit.state', 'com_lotto');
$canDelete  = $user->authorise('core.delete', 'com_lotto');

//Load the Joomla Model framework
jimport('joomla.application.component.model');


//Load com_foo's foobar model. Remember the file name should be foobar.php inside the models folder
JModelLegacy::addIncludePath(JPATH_SITE.'/administrator/components/com_lotto/models');

//Get Instance of Model Object
$winningIns = JModelLegacy::getInstance('winningnumbers', 'LottoModel');

$tiraje_info = JModelLegacy::getInstance('tiraje', 'LottoModel');


?>

<form action="<?php echo htmlspecialchars(JUri::getInstance()->toString()); ?>" method="post"
      name="adminForm" id="adminForm">


	<table class="table table-striped" id="userList">
		<thead>
		<tr class="tickets_table_th">

            <th class='left tickets_table_th'>
                <?php echo JText::_('COM_LOTTO_USERS_TICKETS_TIRAJ'); ?>
            </th>

            <th class='left tickets_table_th'>
                <?php echo JText::_('COM_LOTTO_TICKETS_ID'); ?>
            </th>
            <th class='left tickets_table_th'>
                <?php echo JText::_('COM_LOTTO_NUMBERS'); ?>
            </th>
			<th class='left tickets_table_th'>
                <?php echo JText::_('COM_LOTTO_ADD_NUMBERS'); ?>
            </th>
            <th class='left tickets_table_th'>
                <?php echo JText::_('COM_LOTTO_WIN') ?>
            </th>



        </tr>

		</thead>
		<tfoot>
        <!--
		<tr class="tickets_table_th">
			<td class="tickets_table_th" colspan="<?php echo isset($this->items[0]) ? count(get_object_vars($this->items[0])) : 10; ?>">
				<?php // echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		!-->
		</tfoot>
		<tbody>
		<?php
        if (count($this->items)):


        //print_r ($this->items);
        foreach ($this->items as $i => $item) :
           if ($item->user_id == $user->id ) :
            ?>
			<?php $canEdit = $user->authorise('core.edit', 'com_lotto'); ?>
                <?php //Now you can call the methods inside the model


            $key =$item->tickets_tiraj_numbers;
            $winning_numbs = $winningIns->getWinningnumbers($key);


            $tiraje_i = $tiraje_info->getTirajeInfo($key);
            $winning_numbs =$winning_numbs[0];
            $tiraje_i  =  $tiraje_i[0];

            //  print_r ($tiraje_i);

            $main_winning_numbers_1 =  $winning_numbs->main_winning_numbers;
            $main_winning_numbers =  explode(',',$main_winning_numbers_1  );
            $additional_winning_numbers_1=  $winning_numbs->additional_winning_numbers;
            $additional_winning_numbers =  explode(',',  $additional_winning_numbers_1  );


            ?>
			
			<tr class="row<?php echo $i % 2; ?>">
                <td class="tickets_table_th">

                    <?php

                    $tirajeinf =  $tiraje_info->getTirajeInfobyID($item->tickets_tiraj_numbers);
                    print_r ( $tirajeinf[0]->Tiraje_number);


                    ?>
                </td >

                <td class="tickets_table_th">

					<?php echo $item->tickets_id; ?>
				</td>

                <td class="tickets_table_th">

                    <?php
                    $ticket_winning_numbers =  explode(',',$item->numbers  );

                    if ($main_winning_numbers_1){
                        $html='<div>';
                        $win_numbers_count=0;
                        foreach ($ticket_winning_numbers as $win_num){
                            if (in_array($win_num, $main_winning_numbers)){
                                $style='style="color:green;"';
                                $win_numbers_count +=1;
                            }else{
                                $style='';

                            }
                            if ($win_num) $html .='<span '.$style.' >'.$win_num.', </span>';

                        }
                        $html.='</div>';

                        echo $html;

                    }else{
                        echo $item->numbers;
                    }



                    ?>

                </td>
				 <td class="tickets_table_th">


                    <?php
                    $ticket_winning_numbers =  explode(',',$item->additional_numbers  );


                    if ($additional_winning_numbers_1){
                        $html='<div>';
                        $add_win_numbers_count=0;
                        $additional_winning_numbers_1 =  explode(',', $additional_winning_numbers_1  );
                        foreach ($ticket_winning_numbers as $win_num){
                            if (in_array($win_num, $additional_winning_numbers_1)){
                                $style='style="color:green;"';
                                $add_win_numbers_count +=1;
                            }else{
                                $style='';

                            }
                            if ($win_num) $html .='<span '.$style.' >'.$win_num.', </span>';

                        }
                        $html.='</div>';

                        echo $html;

                    }else{
                        echo $item->additional_numbers ;
                    }

                    ?>

                </td>

                <td class="tickets_table_th">
                

                    <?php

                    $winnum =  json_decode($winning_numbs->winning_prize);
                    $win_pr=null;


                    foreach($winnum as $wnum){

                        if(($wnum[0] == $win_numbers_count) && ($wnum[1] == $add_win_numbers_count)) {

                            $win_pr =   $wnum[2];

                        }




                    }

                    echo $win_pr;

                    ?>

                </td>


			</tr>
		<?php

         endif;

            endforeach;
        else:
           echo '<tr class="row_1"><td colspan="6">' ;

            echo JText::_('COM_LOTTO_NO_TICKETS');
            echo '</td></tr>';

        endif;
        ?>
		</tbody>
	</table>

	<?php if ($canCreate) : ?>
		<a href="<?php echo JRoute::_('index.php?option=com_lotto&task=userform.edit&id=0', false, 0); ?>"
		   class="btn btn-success btn-small"><i
				class="icon-plus"></i>
			<?php echo JText::_('COM_LOTTO_ADD_ITEM'); ?></a>
	<?php endif; ?>

	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<?php if($canDelete) : ?>
<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_LOTTO_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}
</script>
<?php endif; ?>
