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
class LottoViewPayment_processing extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	protected $params;

protected $freekassainfo;

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
        $this->freekassainfo= $this->getFreekassainfo();

        $session = JFactory::getSession();
        $document = JFactory::getDocument();
        $url="https://fonts.googleapis.com/css?family=Chewy";
        $document->addStyleSheet($url);
        $url = "https://fonts.googleapis.com/css?family=Open+Sans";
        $document->addStyleSheet($url);

        $url = "components/com_lotto/assets/css/main_lotto.css";
        $document->addStyleSheet($url);


        $this->state = $this->get('State');
		$this->items =  $session->get('cart');

       $amount =  $this->items['ticket_tiraje'] * count($this->items['tickets_numbers']) * 1;



        $user = JFactory::getUser();


        if (($_GET['status']=='success') || ($_REQUEST['MERCHANT_ID']==$this->freekassainfo[0]->merchant_id)) {

           $this->payment_check();
		   
		   return;

        }

            if ($user->id != 0) {

                if ($amount >= 0) {

                   // $this->update_balace($amount, $user->id);
                 //   $session->clear("cart"); // clear cart
                }

                $this->pagination = $this->get('Pagination');
                $this->params = $app->getParams('com_lotto');

                // Check for errors.
                if (count($errors = $this->get('Errors'))) {
                    throw new Exception(implode("\n", $errors));
                }

                $this->_prepareDocument();

             ///   $this->text = "Оплата Произведен";

            } else {


                $this->text = "Недостаточно средств в балансе для оплаты, Пожалуйста  пройдите регистрацию и пополните баланс ";


            }


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

    public function get_balance($user_id){

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);

        $query
            ->select('*')
            ->from($db->quoteName('#__lotto_users'))
            ->where($db->quoteName('id') . ' = ' . (int)$user_id);

        $db->setQuery($query);

        $results = $db->loadObjectList();
			
      return $results[0]->balance;

    }

    public function update_balace($deducted_amount,$user_id ){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);


        $fields = array(
            $db->quoteName('balance') . ' = ' . $db->quote($deducted_amount),
        );
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($user_id)
        );

        $query->update($db->quoteName('#__lotto_users'))
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

    public function insert_tickets($lotto_tickets, $user_id){

        for($i=1; $i <=$lotto_tickets['ticket_tiraje'];$i++) {
            foreach ($lotto_tickets['tickets_numbers'] as $ky=>$ticket_num) {
                // Create and populate an object.
                $profile = new stdClass();
                $profile->user_id = $user_id;
                $profile->tickets_tiraj_numbers = $lotto_tickets['ticket_tiraje_number'];
                $profile->numbers = implode(",", $ticket_num);
                $profile->additional_numbers = implode(",", $lotto_tickets['additional_fields_tickets'][$ky]);

                $profile->created_data=date("Y-m-d");

                $result = JFactory::getDbo()->insertObject('#__lotto_tickets', $profile);

            }
        }

    }


    public function getFreekassainfo(){


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



    public  function getIP()
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
        return $_SERVER['REMOTE_ADDR'];
    }

    public function payment_check()
    {

        $merchant_id  = $this->freekassainfo[0]->merchant_id; //merchant_id ID мазагина в free-kassa.ru http://free-kassa.ru/merchant/cabinet/help/
        $merchant_secret = $this->freekassainfo[0]->merchant_key; //Секретное слово http://free-kassa.ru/merchant/cabinet/profile/tech.php



        if (!in_array($this->getIP(), array('136.243.38.147', '136.243.38.149', '136.243.38.150', '136.243.38.151', '136.243.38.189', '88.198.88.98'))) {
           // die("hacking attempt!");
        }
     
        $sign = md5($merchant_id . ':' . $_REQUEST['AMOUNT'] . ':' . $merchant_secret . ':' . $_REQUEST['MERCHANT_ORDER_ID']);

        if ($sign != $_REQUEST['SIGN']) {
            die('wrong sign');
        }
		$amount=$_REQUEST['AMOUNT'];
		$user_id = $_REQUEST['MERCHANT_ORDER_ID'];
		$this->send_email();
//Так же, рекомендуется добавить проверку на сумму платежа и не была ли эта заявка уже оплачена или отменена
//Оплата прошла успешно, можно проводить операцию.
		$balance = $this->get_balance($user_id);
		//print_r ($balance );
			$total = $balance+$amount;
			//echo "<br>total=".$total; 
		$this->update_balace($total,$user_id );


        die('YES');

    }

    function send_email(){

        $body = 'test';
        $body_1 = null;
        foreach ($_REQUEST as $key=>$val){
            $body_1 .= "<br>" . $key . "==" . $val;

        }



# Invoke JMail Class
        $mailer = JFactory::getMailer();

        $to = 'proga100@gmail.com';

        $config =& JFactory::getConfig();
        $sender = array($config->get('config.mailfrom'), $config->get('config.fromname') );
        $mailer->setSender($sender);


# Add a recipient -- this can be a single address (string) or an array of addresses
        $recipient = array('tutyou1972@gmail.com', 'proga100@gmail.com');
        $mailer->addRecipient($recipient);


        $mailer->setSubject($body);
        $mailer->setBody($body_1);

# If you would like to send as HTML, include this line; otherwise, leave it out
        $mailer->isHTML();

# Send once you have set all of your options
        $mailer->send();


    }
}