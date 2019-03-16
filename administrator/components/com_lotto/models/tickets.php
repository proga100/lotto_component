<?php

/**
 * @version    CVS: 1.0.0
 * @package    Com_Lotto
 * @author     flance ltd <tutyou1972@gmail.com>
 * @copyright  2018 flance LTD
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Lotto records.
 *
 * @since  1.6
 */
class LottoModelTickets extends JModelList
{
    
        
/**
	* Constructor.
	*
	* @param   array  $config  An optional associative array of configuration settings.
	*
	* @see        JController
	* @since      1.6
	*/
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'tickets_id', 'a.`tickets_id`',
				'user_id', 'a.`user_id`',
				'tickets_tiraj_numbers', 'a.`tickets_tiraj_numbers`',
				'numbers', 'a.`numbers`',
				'invoice_id', 'a.`invoice_id`',
				'created_data', 'a.`created_data`'
			);
		}

		parent::__construct($config);
	}

    
        
    
        
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   Elements order
	 * @param   string  $direction  Order direction
	 *
	 * @return void
	 *
	 * @throws Exception
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		//$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		//$this->setState('filter.state', $published);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_lotto');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.tickets_id', 'asc');

        parent::populateState($ordering, $direction);

        $ordering  = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);
        $direction = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $ordering);

        $this->setState('list.ordering', $ordering);
        $this->setState('list.direction', $direction);

        $start = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0, 'int');
        $limit = $app->getUserStateFromRequest($this->context . '.limit', 'limit', 0, 'int');

        if ($limit == 0)
        {
            $limit = $app->get('list_limit', 0);
        }

        $this->setState('list.limit', $limit);
        $this->setState('list.start', $start);
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return   string A store id.
	 *
	 * @since    1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.state');

                
                    return parent::getStoreId($id);
                
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return   JDatabaseQuery
	 *
	 * @since    1.6
	 */
	protected function getListQuery()
	{


		// Create a new query object.
		$db    = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'DISTINCT a.*'
			)
		);
		$query->from('`#__lotto_tickets` AS a');
                
    	// Filter by search in title
		$search = $this->getState('filter.search');

        $search_user_id = $_REQUEST['user_id'];
        if (!empty( $search_user_id)){
            $query->where('a.user_id = ' .$search_user_id );

        }
		if (!empty($search))
		{
			if (stripos($search, 'tickets_id:') === 0)
			{
				// $query->where('a.tickets_id = ' . (int) substr($search, 3));
			}
			else
			{
			//	$search = $db->Quote('%' . $db->escape($search, true) . '%');

			}
		}


                
		// Add the list ordering clause.
		$orderCol  = $this->state->get('list.ordering', "a.user_id");
		$orderDirn = $this->state->get('list.direction', "ASC");

		if ($orderCol && $orderDirn)
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}



		return $query;
	}

	/**
	 * Get an array of data items
	 *
	 * @return mixed Array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$items = parent::getItems();
                

		return $items;
	}
		
		/* Method to delete record(s)
		*
		* @access public
		* @return boolean True on success
		*/
		
		function delete()
		{
				$cids = JRequest::getVar( 'cid', array(0), 'post', 'array' );
				
				
				$db = JFactory::getDbo();
				
				if (count( $cids )) {
					foreach($cids as $cid) {
						$db->setQuery(
							'DELETE FROM '.$db->quoteName('#__lotto_tickets') .
							' WHERE '.$db->quoteName('tickets_id').' = '.(int) $cid
						);
						$db->Query();
					}
				}
				
				return true;
		}
			public function check_played($ticket_id)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
		
		
		
		$fields = array(
            $db->quoteName('played') . ' = ' . $db->quote(1),
        );
        $conditions = array(
            $db->quoteName('tickets_id') . ' = ' . $db->quote($ticket_id)
        );
		
        $query->update($db->quoteName('#__lotto_tickets'))
            ->set($fields)
            ->where($conditions);

        $db->setQuery($query);
	
        try
        {
            $db->execute();
        }
        catch (RuntimeException $e)
        {
            $e->getMessage();
        }


        $db->execute($query);
		
		
    }
	
		public function get_ticket($ticket_id){

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->quoteName('#__lotto_tickets'))
            ->where($db->quoteName('ticket_id') . ' = ' . (int)$ticket_id);

        $db->setQuery($query);

        $results = $db->loadObjectList();

      return $results[0]->balance;

    }	

}
