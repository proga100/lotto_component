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
class LottoModelFreekassapayments extends JModelAdmin
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
    public $typeAlias = 'com_lotto.freekassapayments';

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
    public function getTable($type = 'freekassapayments', $prefix = 'LottoTable', $config = array())
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
            'com_lotto.freekassapayments', 'freekassapayments',
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
        $data = JFactory::getApplication()->getUserState('com_lotto.edit.freekassapayments.data', array());

        if (empty($data))
        {
            if ($this->item === null)
            {
                $this->item = $this->getItem(0);
            }

            $data = $this->item;

        }

        return $data;
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
                $db->setQuery('SELECT MAX(ordering) FROM #__lotto_freekassapayments');
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
    public function getItem($id)
    {
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
        $query->where($db->quoteName('id') . ' = '. $db->quote($id));



// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();


        return $results;
    }

    public function save($Validdata)
    {

        print_r($Validdata);

        $array = $Validdata;

        $date = JFactory::getDate();


        if (!JFactory::getUser()->authorise('core.admin', 'com_lotto.freekassapayments.' . $array['id']))
        {
            $actions         = JAccess::getActionsFromFile(
                JPATH_ADMINISTRATOR . '/components/com_lotto/access.xml',
                "/access/section[@name='user']/"
            );
            $default_actions = JAccess::getAssetRules('com_lotto.freekassapayments.' . $array['id'])->getData();
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
                'merchant_id',
                'merchant_key'
            )));
        $query->from($db->quoteName('#__lotto_freekassapayments'));
        $query->where($db->quoteName('id') . ' = '. $db->quote($id));

// Reset the query using our newly populated query object.
        $db->setQuery($query);

// Load the results as a list of stdClass objects (see later for more options on retrieving data).
        $results = $db->loadObjectList();




        $array_1['id'] = $array['id'];
        $array_1['merchant_id'] = $array['merchant_id'];
        $array_1['merchant_key'] = $array['merchant_key'];


        if ($results[0]){

            $fields = array(

                $db->quoteName('merchant_id') . ' = ' . $db->quote($array_1['merchant_id']),
                $db->quoteName('merchant_key') . ' = ' . $db->quote($array_1['merchant_key'])
            );

// Conditions for which records should be updated.
            $conditions = array(
                $db->quoteName('id') . ' = '.$array_1['id']

            );

            $query->update($db->quoteName('#__lotto_freekassapayments'))->set($fields)->where($conditions);

            $db->setQuery($query);

            $result = $db->execute();

        }else{



            $object = (object) $array_1;

            // Insert the object into the user profile table.
            $result = JFactory::getDbo()->insertObject('#__lotto_freekassapayments',  $object );
        }
        return true;

    }



}
