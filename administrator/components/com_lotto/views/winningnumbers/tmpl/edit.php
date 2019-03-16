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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_lotto/assets/css/form.css');
?>
<script type="text/javascript">
	js = jQuery.noConflict();
	js(document).ready(function () {
		
	});

	Joomla.submitbutton = function (task) {
		if (task == 'bonus.cancel') {
			Joomla.submitform(task, document.getElementById('user-form'));
		}
		else {
			
			if (task != 'bonus.cancel' && document.formvalidator.isValid(document.id('user-form'))) {

				Joomla.submitform(task, document.getElementById('user-form'));
			}
			else {
				alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
			}
		}
	}
</script>

<form
	action="<?php echo JRoute::_('index.php?option=com_lotto&layout=edit&id=' . (int) $this->item->id); ?>"
	method="post" enctype="multipart/form-data" name="adminForm" id="user-form" class="form-validate">

	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'general', JText::_('COM_LOTTO_CURRENT_TIRAJE', true)); ?>
		<div class="row-fluid">
			<div class="span10 form-horizontal">
				<fieldset class="adminform">

				<?php echo $this->form->renderField('tiraje_number'); ?>
                <?php echo $this->form->renderField('main_winning_numbers'); ?>
                <?php echo $this->form->renderField('additional_winning_numbers'); ?>
                    <?php for($i=1; $i<14;$i++){ ?>
                    <div class="prizes_box">
                        <div class="prize_b_1" ><?php echo $this->form->renderField('winning_prize_number_'.$i); ?></div>
                        <div class="prize_b_2" ><?php echo $this->form->renderField('winning_prize_add_number_'.$i); ?></div>
                        <div class="prize_b_3" ><?php echo $this->form->renderField('winning_prize_'.$i); ?></div>
                    </div>
                        <div style="clear:both;" />
                    <?php } ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>

		<input type="hidden" name="task" value=""/>
		<?php echo JHtml::_('form.token'); ?>

	</div>
</form>
