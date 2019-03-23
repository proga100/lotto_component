<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_login
 *
 * @since  1.5
 */
class ModLottoHelper
{
	
	/**
	 * Returns the current users type
	 *
	 * @return string
	 */
	public static function getType()
	{
		$user = JFactory::getUser();

		return (!$user->get('guest')) ? 'logout' : 'login';
	}
	
	
	
	    public function get_balance(){
		
		
		$user = JFactory::getUser();
		
		$user_id = $user->id;
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
	
}
