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
?>

<div class="position floatfix">

    <div class="tickets_generate">



        <br class="clearfix">

        <div class="ticket_box">
        <?php

        echo '<div class="t_num_tiraje t_head">Номер тиража</div>';
        echo '<div class="t_num t_head">Ставки</div>';
        echo '<div class="t_num_amount t_head">Количество тиражей</div>';
        echo '<div class="clearfix" ></div>';



           foreach ($this->items['tickets_numbers'] as $ky=>$ticket){

         ?>
            <div class="row_tick">

                <?php

                    echo '<div class="t_num_tiraje">'.$this->items['ticket_tiraje_number'].'</div>';

                    echo '<div class="t_num">('.implode(',', $ticket).')';
                    if ($this->items['additional_fields_tickets'][$ky]){
                        echo '('.implode(',', $this->items['additional_fields_tickets'][$ky]).')';
                    }
                    echo '</div>';

                    echo '<div class="t_num_amount">'.$this->items['ticket_tiraje'].'</div>';
                    echo '<div class="clearfix" ></div>';


                ?>
             </div>
            <?php

           }



        ?>
        </div>




        <br class="clearfix">

        <div class="tiraj_calculator">

            <div class="col_tiraj_cal">
                <div class="col_h3">
                    Билетов в игре:
                </div>
                <div class="col_cal" id="col_tiraj_total" data-tiraj-total="<?php echo count($this->items['tickets_numbers']); ?>"><?php echo count($this->items['tickets_numbers']); ?></div>

                <br class="clearfix">
                <div class="col_tiraj_cal">
                    <div class="col_h3">
                        Количество тиражей:
                    </div>
                    <div class="col_cal" id="col_ticket_total" data-ticket-total="<?php echo $this->items['ticket_tiraje']; ?>"><?php echo $this->items['ticket_tiraje']; ?></div>

                </div>
                <br class="clearfix">
                <div class="col_tiraj_cal">
                    <div class="col_h3">
                        Цена билета:
                    </div>
                    <div class="col_cal" id="col_ticket_price" data-ticket-price="1">1$</div>

                </div>
                <br class="clearfix">
                <div class="col_tiraj_final_total">
                    <div class="col_h3">
                        Итого:
                    </div>
                    <div class="col_cal" id="tiiraje_amount" data-tiiraje-amount="<?php echo count($this->items['tickets_numbers']); ?>"><?php echo count($this->items['tickets_numbers']); ?></div>
                    <div class="col_cal" id="cal_mutiplier_1">Х</div>
                    <div class="col_cal" id="ticket_amount" data-ticket-amount="<?php echo $this->items['ticket_tiraje']; ?>"><?php echo $this->items['ticket_tiraje']; ?></div>
                    <div class="col_cal" id="cal_mutiplier_2">Х</div>
                    <div class="col_cal" id="ticket_price" data-ticket-price="1">1$</div>
                    <div class="col_cal" id="cal_equal">=</div>
                    <div class="col_cal" id="cal_total" data-cal-total=""><?php echo $this->items['ticket_tiraje'] * count($this->items['tickets_numbers']) * 1; ?></div>
                </div>

            </div>
            <br class="clearfix">

            <button type="button" id="pay_out" class="pay clearfix">ПОДТВЕРЖДЕНИЕ ОПЛАТЫ</button>

        </div>

    </div>
    <br class="clearfix">

</div>


<script type="text/javascript">

	jQuery(document).ready(function () {


        jQuery('#pay_out').click(function() {

            url = "index.php?option=com_lotto&view=cart_payment";
            jQuery(location).attr("href", url);


        });
	});




</script>





