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
class LottoViewCreatetiraje extends JViewLegacy
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

		JToolBarHelper::title(JText::_('COM_LOTTO_CURRENT_TIRAJE'), 'user.png');

		// If not checked out, can save the item.
		if ( ($canDo->get('core.create')))
		{

			JToolBarHelper::save('createtiraje.save', 'JTOOLBAR_SAVE');
		}





		if (empty($this->item->id))
		{
			JToolBarHelper::cancel('createtiraje.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			JToolBarHelper::cancel('createtiraje.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
