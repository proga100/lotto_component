<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

class LottoViewAjax extends JViewLegacy {

    function display($tpl = null){


        $tickets['tickets_numbers'] = JRequest::getVar("tickets_numbers"); // номера лотто
        $tickets['additional_fields_tickets'] = JRequest::getVar("additional_fields_tickets"); // номера лотто
        $tickets['ticket_tiraje'] = JRequest::getVar("ticket_tiraje"); // количество тиража билетов
        $tickets['ticket_tiraje_number'] = JRequest::getVar("ticket_tiraje_number"); // номер тиража лотто
        $response = array();
        $session = JFactory::getSession();
        $session->set('cart', $tickets);


        $response['tickets_numbers'] = $session->get('cart');
          echo json_encode($response);

        ## Erase cart session data
       // $session->clear('cart');
    }

}