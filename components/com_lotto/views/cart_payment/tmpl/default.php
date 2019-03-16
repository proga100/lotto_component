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

$user = JFactory::getUser();


?>

<div class="position floatfix">

    <div class="tickets_generate">

       <h1> <?php echo  $this->text ?></h1>
        <?php
        if ($this->show_thanks != 1){
        if ($user->id != 0) {

          ?>

        <button type="button" id="balance_add" onclick="location.href = 'index.php?option=com_lotto&view=payment_processing&oam=<?php echo $this->amount ?>';" class="pay clearfix">Пополнение баланса</button>

       <?php  }else{

            ?>
            <button type="button" id="balance_add" onclick="location.href = '/login?view=registration';" class="pay clearfix">Регистрация</button>


       <?php }

        }
        ?>
        <h1> Спасибо </h1>


    </div>
    <br class="clearfix">

</div>






