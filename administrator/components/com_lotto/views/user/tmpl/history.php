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

$user_gamer      = JFactory::getUser($_REQUEST['id']);


//Load the Joomla Model framework
jimport('joomla.application.component.model');


//Load com_foo's foobar model. Remember the file name should be foobar.php inside the models folder
JModelLegacy::addIncludePath(JPATH_SITE.'/administrator/components/com_lotto/models');

//Get Instance of Model Object
$winningIns = JModelLegacy::getInstance('winningnumbers', 'LottoModel');

$tiraje_info = JModelLegacy::getInstance('tiraje', 'LottoModel');



//Load com_foo's foobar model. Remember the file name should be foobar.php inside the models folder
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_lotto/models');

$usersme = JModelLegacy::getInstance('User', 'LottoModel');
$tickets = JModelLegacy::getInstance('Tickets', 'LottoModel');


?>

<form action="<?php echo JRoute::_('index.php?option=com_lotto&view=users'); ?>" method="post"
      name="adminForm" id="adminForm">
    <?php if (!empty($this->sidebar)): ?>
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>

    <div id="j-main-container" class="span10">
        <h2>
            <?php echo JText::_('COM_LOTTO_USERS_NAME').":  ". $user_gamer->name; ?>
        </h2>
        <?php else : ?>
        <div id="j-main-container">
            <?php endif; ?>

            <?php // echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this)); ?>

            <div class="clearfix"></div>
            <table class="table table-striped" id="userList">
                <thead>
                <tr>


                    <th class='left'>
                        <?php echo JHtml::_('searchtools.sort',  'COM_LOTTO_USERS_TICKETS_TIRAJ', 'a.`tickets_tiraj_numbers`', $listDirn, $listOrder); ?>
                    </th>

                    <th class='left'>


                    </th>


                    <th class='left'>

                    </th>
                    <th class='left'>

                    </th>








                </tr>
                </thead>
                <tfoot>

                </tfoot>
                <tbody>
                <?php


                    $db =& JFactory::getDBO();
                    $query = "SELECT * FROM #__lotto_tickets WHERE user_id=".(int) $_REQUEST['id'] ;
                    if ($_REQUEST['tiraje_id']){
                        $query .= "  AND   tickets_tiraj_numbers = ".$_REQUEST['tiraje_id'];

                    }

                    $db->setQuery($query);

                    $rows = $db->loadObjectList();


                    $ordering   = ($listOrder == 'a.ordering');
                    $canCreate  = $user->authorise('core.create', 'com_lotto');
                    $canEdit    = $user->authorise('core.edit', 'com_lotto');
                    $canCheckin = $user->authorise('core.manage', 'com_lotto');
                    $canChange  = $user->authorise('core.edit.state', 'com_lotto');
                    ?>
                    <tr class="row<?php echo $i % 2; ?>">




                        <td colspan="5" class="col_4 sub">

                            <?php

                            $db =& JFactory::getDBO();
                            $query = "SELECT * FROM #__lotto_tickets WHERE user_id=".(int) $_REQUEST['id'] ;
                            if ($_REQUEST['tiraje_id']){
                                $query .= "  AND   tickets_tiraj_numbers = ".$_REQUEST['tiraje_id'];

                            }


                            $db->setQuery($query);

                            $rows = $db->loadObjectList();



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

                                                <?php  echo $key; ?>
                                            </a>
                                        </td>
                                        <td colspan="3" class="col_4  sub">
                                            <table class="t_width">
                                                <tr>
                                                    <th class='left'>


                                                        <?php  echo JText::_(  'COM_LOTTO_TICKETS_ID'); ?>
                                                    </th>


                                                    <th class='left'>
                                                        <?php echo JText::_( 'COM_LOTTO_NUMBERS'); ?>
                                                    </th>
                                                    <th class='left'>
                                                        <?php echo JText::_( 'COM_LOTTO_NUMBERS_ADDS'); ?>
                                                    </th>
                                                    <th class='left' style="text-align: center;">
                                                        <?php echo JText::_(  'COM_LOTTO_WIN'); ?>

                                                    </th>


                                                </tr>
                                                <?php
                                                $ticket_paid_total_price = 0;
                                                //Now you can call the methods inside the model
                                                $winning_numbs = $winningIns->getWinningnumbers($key);


                                                $tiraje_i = $tiraje_info->getTirajeInfo($key);
                                                $winning_numbs =$winning_numbs[0];
                                                $tiraje_i  =  $tiraje_i[0];

                                              //  print_r ($tiraje_i);

                                                $main_winning_numbers_1 =  $winning_numbs->main_winning_numbers;
                                                $main_winning_numbers =  explode(',',$main_winning_numbers_1  );
                                                $additional_winning_numbers_1=  $winning_numbs->additional_winning_numbers;


                                                $additional_winning_numbers_1 =  explode(',',  $additional_winning_numbers_1  );

                                                foreach ($tickets as $tik) {

                                                    $ticket_paid_total_price +=$tik->price;
                                                    ?>
                                                    <tr>
                                                        <td class="table_td_sub">
                                                            <?php  echo $tik->tickets_id; ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $ticket_winning_numbers =  explode(',',$tik->numbers  );

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
                                                                echo $tik->numbers;
                                                            }



                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                            $ticket_winning_numbers =  explode(',',$tik->additional_numbers  );

                                                            if ($additional_winning_numbers_1){
                                                                $html='<div>';
                                                                $add_win_numbers_count=0;

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
                                                                echo $tik->additional_numbers;
                                                            }

                                                            ?>
                                                        </td>
                                                        <td style="text-align: center">
                                                            <?php
                                                            $now = time(); // or your date as well
                                                            $your_date = strtotime($tiraje_i->playing_date);
                                                            $datediff = $your_date- $now;

                                                        
                                                            if ($datediff / (60 * 60 * 24) >0) {
                                                                echo JText::_('COM_LOTTO_PLAYING_DATE');
                                                                echo round($datediff / (60 * 60 * 24));
                                                                echo JText::_('COM_LOTTO_PLAYING_DAY');
                                                            }else{

                                                              $winnum =  json_decode($winning_numbs->winning_prize);
                                                              $win_pr=null;

																foreach($winnum as $wnum){

																	if(($wnum[0] == $win_numbers_count) && ($wnum[1] == $add_win_numbers_count)) {

																		$win_pr =   $wnum[2];

																	}

																}
																echo $win_pr;
															
																
															
																if (empty($tik->played) && isset($win_pr)){
																	 // $usersme->update_balace($win_pr,$userId );
																	 // $winningIns->getPlay($tik->tickets_id);
																}
																
                                                            }

                                                            ?>

                                                        </td>
                                                    </tr>

                                                <?php } ?>

                                            </table>


                                    </tr>


                                </table>



                            <?php



                            }

                            ?>


                        </td>



                    </tr>

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