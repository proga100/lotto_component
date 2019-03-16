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
class LottoViewCart_payment extends JViewLegacy
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
        if ($this->items) {
            if ($user->id != 0) {

                $balance = $this->get_balance($user->id); //  get balance
                $deducted_amount = $balance - $amount;

                if ($deducted_amount >= 0) {


                    $this->update_balace($deducted_amount, $user->id);
                    $this->insert_tickets($this->items, $user->id);

                    $session->clear("cart"); // clear cart

                } else {

                    echo "Недостаточно средств";
                }


                $this->pagination = $this->get('Pagination');
                $this->params = $app->getParams('com_lotto');


                // Check for errors.
                if (count($errors = $this->get('Errors'))) {
                    throw new Exception(implode("\n", $errors));
                }

                $this->_prepareDocument();

                $this->text = "Оплата Произведен";

            } else {


                $this->text = "Недостаточно средств в балансе для оплаты, Пожалуйста  пройдите регистрацию и пополните баланс ";


            }

        }else{

            $this->text = "Пустая Корзинка";
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
}
