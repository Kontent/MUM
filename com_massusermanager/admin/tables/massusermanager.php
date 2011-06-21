<?php
/**
 * Hello World table class
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * Hello Table class
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class TableMassUserManager  extends JTable
{
	/**
	 * Primary Key
	 *
	 * @var int
	 */
	var $id = null;

	/**
	 * @var string
	 */
        
	var $username = '';
	var $name = '';
	var $email = '';
	var $password = '';
	var $userType = 'Registered';

	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function TableMassUserManager(& $db) {
		parent::__construct('#__users', 'id', $db);
	}
}