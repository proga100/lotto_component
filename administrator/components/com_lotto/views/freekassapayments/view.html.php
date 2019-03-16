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
class LottoViewFreekassapayments extends JViewLegacy
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


        $this->item  = $this->get('Item');

        $this->form  = $this->get('Form');

       


		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		LottoHelper::addSubmenu('freekassapayments');


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

        if(!EMPTY($_REQUEST['layout'])){
            $layout = $_REQUEST['layout'];
        }else{

            $layout ='';
        }

		$canDo = LottoHelper::getActions();

		JToolBarHelper::title(JText::_('COM_LOTTO_FREEKASSAPAYMENT'), 'users.png');

		// Check if the form exists before showing the add/edit buttons
		$formPath = JPATH_COMPONENT_ADMINISTRATOR . '/views/freekassapayments';

		if (file_exists($formPath))
		{

            if(($_REQUEST['layout'] !='edit')) {
                if ((($canDo->get('core.create')) && ($this->items))) {

                    JToolBarHelper::addNew('freekassapayments.add', 'JTOOLBAR_EDIT');
                } else {

                    JToolBarHelper::addNew('freekassapayments.add', 'JTOOLBAR_NEW');
                }
            }
            if(($_REQUEST['layout'] =='edit')) {
                // If not checked out, can save the item.
                if (($canDo->get('core.create'))) {

                    JToolBarHelper::save('freekassapayments.save', 'JTOOLBAR_SAVE');
                }
            }


            if ($layout == 'edit') {
                if (empty($this->items)) {
                    JToolBarHelper::cancel('freekassapayments.cancel', 'JTOOLBAR_CANCEL');
                } else {
                    JToolBarHelper::cancel('freekassapayments.cancel', 'JTOOLBAR_CLOSE');
                }

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
				//JToolBarHelper::deleteList('', 'users.delete', 'JTOOLBAR_DELETE');
			}


		}



		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_lotto');
		}

		// Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_lotto&view=freekassapayments');
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

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
            $query->select($db->quoteName(
                array('id',
                    'merchant_id',
                    'merchant_key'
                )));
            $query->from($db->quoteName('#__lotto_freekassapayments'));



// Reset the query using our newly populated query object.
            $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
            $results = $db->loadObjectList();


            return $results;
        }
}
