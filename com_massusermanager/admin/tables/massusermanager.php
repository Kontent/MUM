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

class TableMassUserManager  extends JTable
{
	var $id = null;
	var $username = '';
	var $name = '';
	var $email = '';
	var $password = '';
	var $userType = 'Registered';

	function TableMassUserManager(& $db) {
		parent::__construct('#__users', 'id', $db);
	}
}