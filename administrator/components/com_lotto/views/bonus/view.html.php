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

jimport('joomla.application.component.view');

/**
 * View to edit
 *
 * @since  1.6
 */
class LottoViewBonus extends JViewLegacy
{
	protected $state;

	protected $item;

	protected $form;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  Template name
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	public function display($tpl = null)
	{



		$this->item  = $this->get('Item');


		 $this->form  = $this->get('Form');

  
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		 $this->addToolbar();


		parent::display($tpl);


	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function addToolbar()
	{
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user  = JFactory::getUser();
		$isNew = ($this->item->id == 0);

		$canDo = LottoHelper::getActions();

		JToolBarHelper::title(JText::_('COM_LOTTO_TITLE_BONUS'), 'user.png');

		// If not checked out, can save the item.
		if ( ($canDo->get('core.create')))
		{
			JToolBarHelper::apply('bonus.apply', 'JTOOLBAR_APPLY');
			JToolBarHelper::save('bonus.save', 'JTOOLBAR_SAVE');
		}





		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('bonus.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('bonus.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
