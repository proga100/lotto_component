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
class LottoViewTickets_generate extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

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


		$app = JFactory::getApplication();
        $document = JFactory::getDocument();
        $url="https://fonts.googleapis.com/css?family=Chewy";
        $document->addStyleSheet($url);
        $url = "https://fonts.googleapis.com/css?family=Open+Sans";
        $document->addStyleSheet($url);

        $url = "components/com_lotto/assets/css/main_lotto.css";
        $document->addStyleSheet($url);

        $url = "components/com_lotto/assets/js/index.js";

        $document->addCustomTag('<script src="'.$url.'"></script>');

		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->params = $app->getParams('com_lotto');
        $this->tiraje_info = $this->get_tiraje_info();

		

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
		}

		$this->_prepareDocument();


		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function _prepareDocument()
	{
		$app   = JFactory::getApplication();
		$menus = $app->getMenu();
		$title = null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('COM_LOTTO_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
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

    public function render_lotto_numbers($k, $total_numbers )
    {
        $k = $k+1;
        for ($i = 1; $i <= $total_numbers; $i++) {
            if ($i == $k) {
                echo '<p class="ball">' . $i . '</p>';
                echo ' <br class="clearfix">';
                $k = $k * 2;
            } else {
                echo '<p class="ball">' . $i . '</p>';
            }

        }
    }

    public function height($main_tickets_numbers){

        if (($main_tickets_numbers <= 14) and ($main_tickets_numbers > 0) ){
            $main_height= '100px';
        }elseif(($main_tickets_numbers <= 28) and ($main_tickets_numbers >= 14)){
            $main_height= '200px';
        }elseif(($main_tickets_numbers <= 42) and ($main_tickets_numbers >= 28)){
            $main_height= '250px';
        }elseif(($main_tickets_numbers <= 56) and ($main_tickets_numbers >= 42)){
            $main_height= '300px';
        }

        return $main_height;
    }
    public function get_tiraje_info(){



        $tiraje_number = $_REQUEST['tiraje_number'];
		$tiraje_id = $_REQUEST['tiraje_id'];

        $db = JFactory::getDbo();

// Create a new query object.
            $query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
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
			
			if (!empty($tiraje_id)){
				$query->where($db->quoteName('id') . ' = '. $db->quote($tiraje_id));
			}else{
				
				$query->where($db->quoteName('Tiraje_number') . ' = '. $db->quote($tiraje_number));
			}
      // echo "<pre>"; print_r ( $query);



// Reset the query using our newly populated query object.
            $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
            $results = $db->loadObjectList();
     //       print_r ( $results);

            return $results;



    }
}
