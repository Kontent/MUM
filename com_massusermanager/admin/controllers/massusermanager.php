<?php
 /**
 * Kontent Mass User Manager
 * @version 	$Id: massusermanager.php
 * @package 	Kontent Mass User Manager
 * @copyright 	(C) 2010-2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Hello Controller
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MassUserManagerControllerMassUserManager extends MassUserManagerController
{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
	}

	/**
	 * copy emails instead of usernames
	 * @return void
	 */
	function copy_emails()
	{
		$model = $this->getModel();

		if ($model->copy_emails()) {
			$msg = JText::_( $model->_message.' Users(s) Modified!' );
		} else {
			$msg = JText::_( 'Error Modifying Users' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_massusermanager';
		$this->setRedirect($link, $msg);
	}

	/**
	 * create new passwords and mail info to the users
	 * @return void
	 */
	function create_random_passwords()
	{
		$model = $this->getModel();

		if ($model->create_random_passwords()) {
			$msg = JText::_( $model->_message.' Users(s) Modified!' );
		} else {
			$msg = JText::_( 'Error Modifying Users' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_massusermanager';
		$this->setRedirect($link, $msg);
	}

	/**
	 * force users to change password
	 * @return void
	 */
	function force_password_change()
	{
		$model = $this->getModel();

		if ($model->force_password_change()) {
			$msg = JText::_( $model->_message.' Users(s) Forced To Change Password!' );
		} else {
			$msg = JText::_( 'Error Forced Users To Change Password' );
		}

		// Check the table in so it can be edited.... we are done with it anyway
		$link = 'index.php?option=com_massusermanager';
		$this->setRedirect($link, $msg);
	}

	/**
	 * cancel editing a record
	 * @return void
	 */
	function cancel()
	{
		$msg = JText::_( 'Operation Cancelled' );
		$this->setRedirect( 'index.php?option=com_massusermanager', $msg );
	}
}