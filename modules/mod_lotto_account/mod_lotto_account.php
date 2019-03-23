<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the login functions only once
JLoader::register('ModLottoHelper', __DIR__ . '/helper.php');


$balance             = ModLottoHelper::get_balance();
$user             = JFactory::getUser();
$layout           = $params->get('layout', 'default');

// Logged users must load the logout sublayout


require JModuleHelper::getLayoutPath('mod_lotto_account', $layout);
