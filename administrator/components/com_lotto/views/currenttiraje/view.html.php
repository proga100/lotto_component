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
 * View class for a list of Lotto.
 *
 * @since  1.6
 */
class LottoViewCurrenttiraje extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

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
		//$this->state = $this->get('State');
		$this->items = $this->getItems();

        $this->filterForm = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		LottoHelper::addSubmenu('currenttiraje');


		$this->addToolbar();


		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return void
	 *
	 * @since    1.6
	 */
	protected function addToolbar()
	{

		$canDo = LottoHelper::getActions();

		JToolBarHelper::title(JText::_('COM_LOTTO_CURRENT_TIRAJE'), 'users.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/createtiraje';

		if (file_exists($formPath))
		{
			if ($canDo->get('core.create'))
			{
				JToolBarHelper::addNew('createtiraje.add', 'JTOOLBAR_NEW');


			}


		}

		if ($canDo->get('core.edit.state'))
		{
			if (isset($this->items[0]->state))
			{
				JToolBarHelper::divider();
				JToolBarHelper::custom('users.publish', 'publish.png', 'publish_f2.png', 'JTOOLBAR_PUBLISH', true);
				JToolBarHelper::custom('users.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
			}
			elseif (isset($this->items[0]))
			{
				// If this component does not use state then show a direct delete button as we can not trash
				JToolBarHelper::deleteList('', 'users.delete', 'JTOOLBAR_DELETE');
			}


		}



		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_lotto');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_lotto&view=currenttiraje');
	}




    /**
     * Check if state is set
     *
     * @param   mixed  $state  State
     *
     * @return bool
     */
    public function getState($state)
    {
        return isset($this->state->{$state}) ? $this->state->{$state} : false;
    }

        public function getItems(){


            // Get a db connection.
            $db = JFactory::getDbo();

// Create a new query object.
            $query = $db->getQuery(true);

// Select all records from the user profile table  key begins with "custom.".
// Order it by the ordering field.
            $query->select($db->quoteName(
                array('id',
                'Tiraje_number',
                'selection_numbers',
                'ticket_total_numbers',
                'additional_selection_numbers',
                'additional_ticket_total_numbers',
                'playing_date',
                'ticket_price'
            )));
            $query->from($db->quoteName('#__lotto_tiraje'));



// Reset the query using our newly populated query object.
            $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
            $results = $db->loadObjectList();


            return $results;
        }
}
