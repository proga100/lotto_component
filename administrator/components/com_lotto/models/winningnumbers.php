<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Lotto
 * @author     flance ltd <tutyou1972@gmail.com>
 * @copyright  2018 flance LTD
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Lotto model.
 *
 * @since  1.6
 */
class LottoModelWinningnumbers extends JModelAdmin
{
    /**
     * @var      string    The prefix to use with controller messages.
     * @since    1.6
     */
    protected $text_prefix = 'COM_LOTTO';

    /**
     * @var   	string  	Alias to manage history control
     * @since   3.2
     */
    public $typeAlias = 'com_lotto.winningnumbers';

    /**
     * @var null  Item data
     * @since  1.6
     */
    protected $item = null;






    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string  $type    The table type to instantiate
     * @param   string  $prefix  A prefix for the table class name. Optional.
     * @param   array   $config  Configuration array for model. Optional.
     *
     * @return    JTable    A database object
     *
     * @since    1.6
     */
    public function getTable($type = 'Winningnumbers', $prefix = 'LottoTable', $config = array())
    {


        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array    $data      An optional array of data for the form to interogate.
     * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm  A JForm object on success, false on failure
     *
     * @since    1.6
     */
    public function getForm($data = array(), $loadData = true)
    {


        // Initialise variables.
        $app = JFactory::getApplication();

        // Get the form.
        $form = $this->loadForm(
            'com_lotto.winningnumbers', 'winningnumbers',
            array('control' => 'jform',
                'load_data' => $loadData
            )
        );



        if (empty($form))
        {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return   mixed  The data for the form.
     *
     * @since    1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState('com_lotto.edit.winningnumbers.data', array());

        if (empty($data))
        {
            if ($this->item === null)
            {
                $this->item = $this->getItem();
            }

            $data = $this->item;

        }

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer  $pk  The id of the primary key.
     *
     * @return  mixed    Object on success, false on failure.
     *
     * @since    1.6
     */
    public function getItem($pk = null)
    {


        if ($item = parent::getItem($pk))
        {
            // Do any procesing on fields here if needed
        }

        return $item;

    }

    /**
     * Method to duplicate an User
     *
     * @param   array  &$pks  An array of primary key IDs.
     *
     * @return  boolean  True if successful.
     *
     * @throws  Exception
     */
    public function duplicate(&$pks)
    {
        $user = JFactory::getUser();

        // Access checks.
        if (!$user->authorise('core.create', 'com_lotto'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $dispatcher = JEventDispatcher::getInstance();
        $context    = $this->option . '.' . $this->name;

        // Include the plugins for the save events.
        JPluginHelper::importPlugin($this->events_map['save']);

        $table = $this->getTable();

        foreach ($pks as $pk)
        {

            if ($table->load($pk, true))
            {
                // Reset the id to create a new record.
                $table->id = 0;

                if (!$table->check())
                {
                    throw new Exception($table->getError());
                }


                // Trigger the before save event.
                $result = $dispatcher->trigger($this->event_before_save, array($context, &$table, true));

                if (in_array(false, $result, true) || !$table->store())
                {
                    throw new Exception($table->getError());
                }

                // Trigger the after save event.
                $dispatcher->trigger($this->event_after_save, array($context, &$table, true));
            }
            else
            {
                throw new Exception($table->getError());
            }

        }

        // Clean cache
        $this->cleanCache();

        return true;
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   JTable  $table  Table Object
     *
     * @return void
     *
     * @since    1.6
     */
    protected function prepareTable($table)
    {
        jimport('joomla.filter.output');

        if (empty($table->id))
        {
            // Set ordering to the last item if not set
            if (@$table->ordering === '')
            {
                $db = JFactory::getDbo();
                $db->setQuery('SELECT MAX(ordering) FROM #__lotto_winning_numbers');
                $max             = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   JTable  $table  Table Object
     *
     * @return void
     *
     * @since    1.6
     */
    public function getWinningnumbers($tirajenumber)
    {
        // Get a db connection.
        $db = JFactory::getDbo();
		
		

// Create a new query object.
        $query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
        $query->select($db->quoteName(
            array('id',
                'tiraje_number',
                'main_winning_numbers',
                'additional_winning_numbers',
                'winning_prize')));
        $query->from($db->quoteName('#__lotto_winning_numbers'));
        $query->where($db->quoteName('tiraje_number') . ' = '. $db->quote($tirajenumber));



// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();


        return $results;
    }

    public function save($Validdata){

        print_r ($Validdata);
        $array = $Validdata;


        $date = JFactory::getDate();


        if (!JFactory::getUser()->authorise('core.admin', 'com_lotto.winningnumbers.' . $array['id']))
        {
            $actions         = JAccess::getActionsFromFile(
                JPATH_ADMINISTRATOR . '/components/com_lotto/access.xml',
                "/access/section[@name='user']/"
            );
            $default_actions = JAccess::getAssetRules('com_lotto.winningnumbers.' . $array['id'])->getData();
            $array_jaccess   = array();

            foreach ($actions as $action)
            {
                if (key_exists($action->name, $default_actions))
                {
                    $array_jaccess[$action->name] = $default_actions[$action->name];
                }
            }

            $array['rules'] = $this->JAccessRulestoArray($array_jaccess);
        }

        // Bind the rules for ACL where supported.
        if (isset($array['rules']) && is_array($array['rules']))
        {
            $this->setRules($array['rules']);
        }



        // Get a db connection.
        $db = JFactory::getDbo();

// Create a new query object.
        $query = $db->getQuery(true);

// Select all records from the user profile table where key begins with "custom.".
// Order it by the ordering field.
        $query->select($db->quoteName(
            array('id',
                'tiraje_number',
                'main_winning_numbers',
                'additional_winning_numbers',
                'winning_prize'
            )));
        $query->from($db->quoteName('#__lotto_winning_numbers'));
        $query->where($db->quoteName('tiraje_number') . ' = '. $db->quote($array['tiraje_number']));


// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();

        for($i=1; $i<14;$i++) {
            if ( $array['winning_prize_number_'.$i] && $array['winning_prize_'.$i]) {
                $win_pri[] = array(
                    $array['winning_prize_number_' . $i],
                    $array['winning_prize_add_number_' . $i],
                    $array['winning_prize_' . $i]
                );
                unset($array['winning_prize_number_' . $i]) ;
                unset($array['winning_prize_add_number_' . $i]);
                unset( $array['winning_prize_' . $i]);

            }

        }



        $array_1['tiraje_number'] = $array['tiraje_number'];
        $array_1['main_winning_numbers'] = $array['main_winning_numbers'];
        $array_1['additional_winning_numbers'] = $array['additional_winning_numbers'];
        $array_1['winning_prize'] = json_encode($win_pri);
        if ($results[0]){

            $fields = array(
                $db->quoteName('tiraje_number') . ' = ' . $db->quote($array_1['tiraje_number']),
                $db->quoteName('main_winning_numbers') . ' = ' . $db->quote($array_1['main_winning_numbers']),
                $db->quoteName('additional_winning_numbers') . ' = ' . $db->quote($array_1['additional_winning_numbers']),
                $db->quoteName('winning_prize') . ' = ' . $db->quote($array_1['winning_prize'])
            );

// Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('tiraje_number') . ' = '.$array_1['tiraje_number']

            );

            $query->update($db->quoteName('#__lotto_winning_numbers'))->set($fields)->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

        }else{



            $object = (object) $array_1;

            // Insert the object into the user profile table.
            $result = JFactory::getDbo()->insertObject('#__lotto_winning_numbers',  $object );
        }



        return true;
    }
	
	public function getPlay($ticket_id)
    {
		
		
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
		
		
		
		$fields = array(
            $db->quoteName('played') . ' = ' . $db->quote('1'),
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
