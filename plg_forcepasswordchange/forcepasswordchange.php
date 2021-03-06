<?php

 /**
 * Kontent Mass User Manager
 * @version 	$Id: forcepasswordchange.php
 * @package 	Kontent Mass User Manager
 * @copyright 	(C) 2010-2011 Kontent Design. All rights reserved.
 * @copyright   (C) 2010 by Source Coast - All rights reserved
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class plgSystemForcePasswordChange extends JPlugin
{
	function plgSystemForcePasswordChange(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	function onAfterRoute()
	{
                global $mainframe;

                // Don't do anything if this is the administrator backend or debugging is on
                if($mainframe->isAdmin() || JDEBUG) {
                        return;
                }

		$user = &JFactory::getUser();

		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');
		$layout = JRequest::getVar('layout');
		// no_html is sent by Mighty Registration for ajax checks, so we need to ignore them
		$noHtml = JRequest::getVar('no_html');

		$editProfileOption = "com_user";
		$editProfileLayout = "form";
		$editProfileSaveTask = "save";
		$editProfileView = "user";

		if(!$user->guest && $user->lastvisitDate == "0000-00-00 00:00:01" && $noHtml != "1")
		{
			// The user is not a guest and their lastvisitDate is zeros
			if($option == $editProfileOption && $task == $editProfileSaveTask)
			{
				// The user is saving their profile

				// Set the last visit date to a real value so we won't continue forcing them to update their profile
				$user->setLastVisit();
				$date = JFactory::getDate();
				$user->lastvisitDate = $date->toMySQL();
			}
			else if(!($option == $editProfileOption && $view == $editProfileView && $layout == $editProfileLayout))
			{
				// The user is not on the edit profile form

				// Update lastvisitDate back to zero
				$dbo = &JFactory::getDBO();
				$query = "UPDATE #__users ".
					"SET lastvisitDate = ".$dbo->quote("0000-00-00 00:00:01")." ".
					"WHERE id = ".$dbo->quote($user->id);
				$dbo->setQuery($query);
				$dbo->query();
	
				// Redirect to edit profile
				$lang =& JFactory::getLanguage();
				$lang->load('plg_system_forcepasswordchange', JPATH_ADMINISTRATOR);

				$app = &JFactory::getApplication();

				$app->redirect(
					"index.php?option=".$editProfileOption."&view=".$editProfileView."&layout=".$editProfileLayout,
					JText::_('PLG_KMUM_UPDATE_YOUR_PASSWORD')
				);
			}
		}
	}
}
