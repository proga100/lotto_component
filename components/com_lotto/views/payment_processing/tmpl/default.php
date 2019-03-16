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

$fk_merchant_id = $this->freekassainfo[0]->merchant_id; //merchant_id ID мазагина в free-kassa.ru http://free-kassa.ru/merchant/cabinet/help/
$fk_merchant_key = $this->freekassainfo[0]->merchant_key; //Секретное слово http://free-kassa.ru/merchant/cabinet/profile/tech.php
$user =& JFactory::getUser();
$user_id = $user->id;

if ($_GET['status']=='fail') { ?>

    <h2>Оплата не произведен.</h2>
        <div id="error">Ошибка оплаты</div>

<?php
return;
}

if ($_GET['status']=='complete') { ?>
    <h1>Спасибо за покупку !</h1>

    <h2>Оплата  произведен.</h2>


    <?php
    return;
}

if (isset($_GET['prepare_once'])) {
    $hash = md5($fk_merchant_id.":".$_GET['oa'].":".$fk_merchant_key.":".$_GET['fr']);
    echo '<hash>'.$hash.'</hash>';
    exit;
}

?>
<script type="text/javascript">
	   var min = 1;
		
    function calculate() {
        var re = /[^0-9\.]/gi;
        var url = window.location.href;
        var desc = jQuery('#desc').val();
        var sum = jQuery('#sum').val();
        if (re.test(sum)) {
            sum = sum.replace(re, '');
            jQuery('#oa').val(sum);
        }
        if (sum < min) {
            jQuery('#error').html('Сумма должна быть больше '+min);
            jQuery('#submit').attr("disabled", "disabled");
            return false;
        } else {
            jQuery('#error').html('');
        }
        if (desc.length < 1) {
            jQuery('#error').html('Необходимо ввести номер заявки');
            return false;
        }
        console.log(sum);



        jQuery.get('http://<?php echo $_SERVER["SERVER_NAME"] . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) ?>' +'?index.php?option=com_lotto&view=payment_processing&prepare_once=1&fr='+desc+'&oa='+sum, function(data) {
            var re_anwer = /<hash>([0-9a-z]+)<\/hash>/gi;
            jQuery('#s').val(re_anwer.exec(data)[1]);
            jQuery('#submit').removeAttr("disabled");
        });

    }
	jQuery( document ).ready(function() {
				 
			jQuery('#sum').val('<?php echo $_REQUEST['oam'] ?>');
             calculate();
	});
</script>

<div class="position floatfix">

    <div class="tickets_generate">

       <h1> <?php echo  $this->text ?></h1>
        <h2>Оплата через <a href="http://wwww.free-kassa.ru">free-kassa.ru</a></h2>
        <div id="error"></div>
        <form method=GET action="http://www.free-kassa.ru/merchant/cash.php">
            <input type="hidden" name="m" value="<?=$fk_merchant_id?>">
            <input type="text" name="oa" id="sum" id="oa" onchange="calculate()" onkeyup="calculate()" onfocusout="calculate()" onactivate="calculate()" ondeactivate="calculate()" > Введите сумму для пополнения баланса
            <input type="hidden" name="s" id="s" value="0">
            <br>
            <input type="hidden" name="o" id="desc" value="<?php echo $user_id ; ?>" onchange="calculate()" onkeyup="calculate()" onfocusout="calculate()" onactivate="calculate()" ondeactivate="calculate()">
            <input type="submit" id="submit" value="Оплатить" disabled>
        </form>


    </div>
    <br class="clearfix">

</div>






