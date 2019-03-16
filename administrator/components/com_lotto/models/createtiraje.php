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
class LottoModelCreatetiraje extends JModelAdmin
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
    public $typeAlias = 'com_lotto.createtiraje';

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
    public function getTable($type = 'Tiraje', $prefix = 'LottoTable', $config = array())
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
            'com_lotto.createtiraje', 'createtiraje',
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
        $data = JFactory::getApplication()->getUserState('com_lotto.edit.createtiraje.data', array());

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
                $db->setQuery('SELECT MAX(ordering) FROM #__lotto_tiraje');
                $max             = $db->loadResult();
                $table->ordering = $max + 1;
            }
        }
    }

    public function save($Validdata){

        print_r ($Validdata);

        $array = $Validdata;

        $date = JFactory::getDate();




        if (!JFactory::getUser()->authorise('core.admin', 'com_lotto.tiraje.' . $array['id']))
        {
            $actions         = JAccess::getActionsFromFile(
                JPATH_ADMINISTRATOR . '/components/com_lotto/access.xml',
                "/access/section[@name='user']/"
            );
            $default_actions = JAccess::getAssetRules('com_lotto.tiraje.' . $array['id'])->getData();
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
        $query->select($db->quoteName(array('id',
            'Tiraje_number',
            'selection_numbers',
            'ticket_total_numbers',
            'additional_selection_numbers',
            'additional_ticket_total_numbers',
            'playing_date',
            'ticket_price'
        )));
        $query->from($db->quoteName('#__lotto_tiraje'));
        $query->where($db->quoteName('Tiraje_number') . ' = '. $db->quote($array['Tiraje_number']));


// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();


        if ($results[0]){

            $fields = array(
                $db->quoteName('Tiraje_number') . ' = ' . $db->quote($array['Tiraje_number']),
                $db->quoteName('selection_numbers') . ' = ' . $db->quote($array['selection_numbers']),
                $db->quoteName('ticket_total_numbers') . ' = ' . $db->quote($array['ticket_total_numbers']),
                $db->quoteName('additional_selection_numbers') . ' = ' . $db->quote($array['additional_selection_numbers']),
                $db->quoteName('additional_ticket_total_numbers') . ' = ' . $db->quote($array['additional_ticket_total_numbers']),
                $db->quoteName('playing_date') . ' = ' . $db->quote($array['playing_date']),
                $db->quoteName('ticket_price') . ' = ' . $db->quote($array['ticket_price'])

            );

// Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('Tiraje_number') . ' = '.$array['Tiraje_number']

            );

            $query->update($db->quoteName('#__lotto_tiraje'))->set($fields)->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

        }else{

            $object = (object) $array;


            // Insert the object into the user profile table.
            $result = JFactory::getDbo()->insertObject('#__lotto_tiraje',  $object );
        }


        return true;
    }
}
