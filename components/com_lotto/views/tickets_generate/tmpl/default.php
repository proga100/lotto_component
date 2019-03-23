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
        <?php


       $tiraj_info =  $this->tiraje_info[0];

		

        $tiraje_number= $tiraj_info->Tiraje_number; ?>

        <div class="tiraj_sel">
            <h2> Тираж    № <?php echo $tiraje_number; ?> </h2> 
			<h2> <?php 
			
			 $source = $tiraj_info->playing_date ;
				$date = new DateTime($source);
				
			
			echo JText::_('COM_LOTTO_PLAYING_DATE', true)  ."   ".$date->format('d.m.Y');; ?> </h2>
			<br/>
			
			<?php $today = date("Y-m-d H:i:s");
				
			
				if ( $source < $today) {
					
					echo "<h2 class='playing_over'> ". JText::_('COM_LOTTO_PLAYING_OVER', true)."</h2>";  
				
				return; 
					
				}
				
				?>
			
			
            <div id="ticket_tiraje_number" data-tiraje-number="<?php echo $tiraje_number; ?>"></div>
            <div class="col_tiraj">
                <div class="col_h3">
                    Количество тиражей:
                </div>
                <div class="col" id="col_tiraj_1" data-num-tiraj="1">1 </div>
                <div class="col" id="col_tiraj_2" data-num-tiraj="2">2</div>
                <div class="col" id="col_tiraj_3" data-num-tiraj="3">3 </div>
                <div class="col" id="col_tiraj_4" data-num-tiraj="4">4</div>
                <div class="col" id="col_tiraj_5" data-num-tiraj="5">5</div>
                <div class="col" id="col_tiraj_6" data-num-tiraj="6">6</div>
            </div>
            <br class="clearfix">
            <div class="col_tiraj">
                <div class="col_h3">
                    Количество полей:
                </div>
                <div class="col" id="col_ticket_3" data-num="3">3</div>
                <div class="col" id="col_ticket_6" data-num="6">6</div>
                <div class="col" id="col_ticket_9" data-num="9">9</div>
                <div class="col" id="col_ticket_12" data-num="12">12</div>
                <div class="col" id="col_ticket_15" data-num="15">15</div>
            </div>

        </div>
        <br class="clearfix">


        <?php

        $tickets = 15;
        $main_tickets_numbers = $tiraj_info->ticket_total_numbers;
        $main_selection_numbers=  $tiraj_info->selection_numbers;
        $add_tickets_numbers = $tiraj_info->additional_ticket_total_numbers;
        $add_selection_numbers  =$tiraj_info->additional_selection_numbers;


        for ($t_number =1; $t_number <= $tickets;  $t_number++) {


            ?>
            <div id="ticket_boxes_<?php echo $t_number ?>" class="wrapper ticket_boxes" data-tnumber="<?php echo $t_number ?>">
                <button type="button" id="generate" class="generate clearfix">Авто-выбор</button>

                <div class="main">

                       <?php
                       $this->render_lotto_numbers(6, $main_tickets_numbers );
                       ?>
                </div>

                <div class="additional">
                    <?php
                    $this->render_lotto_numbers(6, $add_tickets_numbers );
                    ?>


                </div>
                <button type="button" id="confirm" class="confirm clearfix">Подтвердить выбор</button>

            </div>
        <?php } ?>




        <br class="clearfix">
        <br class="clearfix">

        <div class="tiraj_calculator">

            <div class="col_tiraj_cal">
                <div class="col_h3">
                    Билетов в игре:
                </div>
                <div class="col_cal" id="col_tiraj_total" data-tiraj-total="0">0</div>

                <br class="clearfix">
                <div class="col_tiraj_cal">
                    <div class="col_h3">
                        Количество тиражей:
                    </div>
                    <div class="col_cal" id="col_ticket_total" data-ticket-total="3">3</div>

                </div>
                <br class="clearfix">
                <div class="col_tiraj_cal">
                    <div class="col_h3">
                        Цена билета:
                    </div>
                    <div class="col_cal" id="col_ticket_price" data-ticket-price="<?php echo $tiraj_info->ticket_price; ?>"><?php echo $tiraj_info->ticket_price; ?>$</div>

                </div>
                <br class="clearfix">
                <div class="col_tiraj_final_total">
                    <div class="col_h3">
                        Итого:
                    </div>
                    <div class="col_cal" id="tiiraje_amount" data-tiiraje-amount="0">0</div>
                    <div class="col_cal" id="cal_mutiplier_1">Х</div>
                    <div class="col_cal" id="ticket_amount" data-ticket-amount="1">1</div>
                    <div class="col_cal" id="cal_mutiplier_2">Х</div>
                    <div class="col_cal" id="ticket_price" data-ticket-price="<?php echo $tiraj_info->ticket_price; ?>"><?php echo $tiraj_info->ticket_price; ?>$</div>
                    <div class="col_cal" id="cal_equal">=</div>
                    <div class="col_cal" id="cal_total" data-cal-total="">6</div>
                </div>

            </div>
            <br class="clearfix">

            <button type="button" id="pay" class="pay clearfix">ОПЛАТИТЬ</button>

        </div>

    </div>
    <br class="clearfix">

</div>


<script type="text/javascript">

	jQuery(document).ready(function () {
		jQuery('.delete-button').click(deleteItem);
	});

	function deleteItem() {

		if (!confirm("<?php echo JText::_('COM_LOTTO_DELETE_MESSAGE'); ?>")) {
			return false;
		}
	}

    jQuery(document).ready(function () {

        jQuery('.main').css('height', '<?php echo $this->height($main_tickets_numbers); ?>');
        jQuery(".additional").css('height', '<?php echo $this->height( $add_tickets_numbers); ?>');
        var main_tickets_numbers = <?php echo $main_tickets_numbers; ?>;
        var main_selection_numbers = <?php echo $main_selection_numbers; ?>;
        var add_tickets_numbers = <?php echo $add_tickets_numbers; ?>;

        var add_selection_numbers =  <?php echo $add_selection_numbers; ?>;

        jQuery('.generate').click(function () {



            tickets_num("main", main_tickets_numbers, main_selection_numbers, this);

            <?php if ($add_tickets_numbers >0) echo 'tickets_num("additional",add_tickets_numbers,add_selection_numbers,this);' ; ?>


        });


        function tickets_num(box, main_tickets_numbers, main_selection_numbers, ball_box) {
            //get random numbers


            var numbers = [];
            if (box == "main") {
                var ball = jQuery(ball_box).parent().find(".main .ball");
                console.log(box);
            } else if (box == "additional") {

                var ball = jQuery(ball_box).parent().find(".additional .ball");
            }

            //console.log(ball);

            while (numbers.length < main_selection_numbers) {

                var random = Math.floor(Math.random() * main_tickets_numbers) + 1;

                if (numbers.indexOf(random) == -1) {
                    numbers.push(random);
                }
            }

            //sort numbers in array

            numbers.sort(function (a, b) {
                return a - b;
            });

            //color balls

            for (var i = 0; i < ball.length; i++) {

                ball[i].style.backgroundColor = "white";
                ball[i].style.color = "#3d3c3a";

                for (var j = 0; j < numbers.length; j++) {
                    if (numbers[j] == parseInt(ball[i].innerHTML)) {
                        ball[i].style.backgroundColor = "#8a8b8d";
                        ball[i].style.color = "white";
                    }
                }
            }

            //add numbers to history

            var history = [];
            var historyDiv = document.getElementById("history");
            var para = document.createElement("p");
            history.push(numbers.join(", "));

            for (var k in history) {

                var node = document.createTextNode(history[k]);
                para.appendChild(node);
                //   historyDiv.appendChild(para);

            }


        }

        jQuery('.ball').click(function () {

            ballclick(this, main_selection_numbers, add_selection_numbers);

        });

        function ballclick(box_ball,main_selection_numbers,add_selection_numbers){

            console.log(jQuery(box_ball).parent().attr("class"));

            if (jQuery(box_ball).parent().attr("class") == "main"){
                var tickets_amount = main_selection_numbers;
            }else if (jQuery(box_ball).parent().attr("class") == "additional"){

                var tickets_amount =add_selection_numbers;
            }
            if (jQuery(box_ball).css("color") != 'rgb(255, 255, 255)') {
                var ball = jQuery(box_ball).parent().find(".ball");
                var k = 1;
                jQuery.each(ball, function (index, value) {

                    if (jQuery(value).css("color") == 'rgb(255, 255, 255)') {
                        // console.log(value);

                        k++;
                    }
                });
                if (k > tickets_amount) {

                    alert("Вы можете выбират только "+tickets_amount+" номеров")

                } else {
                    set_colors_clicked(box_ball);
                }

            }else{
                jQuery(box_ball).css("background","white");
                jQuery(box_ball).css("color","#8a8b8d");

            }
        }

    });

    function set_colors_clicked(obj){

        jQuery(obj).css("background","#8a8b8d");
        jQuery(obj).css("color","white");

    }



</script>

