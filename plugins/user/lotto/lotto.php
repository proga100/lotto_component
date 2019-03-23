<?php
/**
 * Social Login
 *
 * @version 	1.0
 * @author		flance
 * @copyright	© 2012. All rights reserved.
 * @license 	GNU/GPL v.3 or later.
 */

// No direct access
defined('_JEXEC') or die;

class plgUserLotto extends JPlugin
{
	/**
	 * Remove all sessions for the user name
	 *
	 * Method is called after user data is deleted from the database
	 *
	 * @param	array		$user	Holds the user data
	 * @param	boolean		$succes	True if user was succesfully stored in the database
	 * @param	string		$msg	Message
	 *
	 * @return	boolean
	 * @since	1.6
	 */
	public function onUserAfterDelete($user, $succes, $msg)
	{
		if (!$succes) {
			return false;
		}
		
	//	JPluginHelper::importPlugin('slogin_integration');
        $dispatcher = JDispatcher::getInstance();
		$dispatcher->trigger('onAfterSloginDeleteUser',array((int)$user['id']));
		
		$db = JFactory::getDbo();
		$db->setQuery(
			'DELETE FROM '.$db->quoteName('#__lotto_users') .
			' WHERE '.$db->quoteName('id').' = '.(int) $user['id']
		);
		$db->Query();
		
		$db->setQuery(
			'DELETE FROM '.$db->quoteName('#__lotto_tickets') .
			' WHERE '.$db->quoteName('user_id').' = '.(int) $user['id']
		);
		$db->Query();
		
		
		
		return true;
	}



    
	public function onUserAfterSave($data, $isNew, $result, $error){
		$userId	= JArrayHelper::getValue($data, 'id', 0, 'int');
		$userName	= JArrayHelper::getValue($data, 'name', '', 'string');
				
		if ($isNew == 1) {
	
			$db = JFactory::getDbo();
			
			$db->setQuery(
				'SELECT amount FROM #__lotto_bonuses' 
			);
			
			$results = $db->loadRowList();
			$bonus = $results[0][0];
			
			// Create an object for the record we are going to update.
			$object = new stdClass();

			// Must be a valid primary key value.
			$object->id = 	$userId	;
			$object->name = $userName;
			$object->tickets = '' ;
			$object->balance = $bonus ;
			$object->state = 1;
			$object->ordering = 0;
			
			// Update their details in the users table using id as the primary key.
			$result = JFactory::getDbo()->insertObject('#__lotto_users', $object);
			
		}
		
		
	}
}
