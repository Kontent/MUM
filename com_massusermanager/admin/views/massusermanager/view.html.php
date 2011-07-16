<?php

 /**
 * Kontent Mass User Manager
 * @version 	$Id: view.html.php
 * @package 	Kontent Mass User Manager
 * @copyright 	(C) 2010-2011 Kontent Design. All rights reserved.
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link 		http://extensions.kontentdesign.com
 **/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

JHTML::_('behavior.mootools');
JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'massusermanager.css', 'administrator/components/com_massusermanager/assets/css/');

class MassUserManagerViewMassUserManager extends JView
{
	/**
	 * view display method
	 * @return void
	 **/
	function display($tpl = null)
	{
                
		JToolBarHelper::title(   JText::_( 'Mass User Management' ), 'generic.png' );
		JToolBarHelper::custom("create_random_passwords","sendmail","sendmail","Create Random Passwords",false);
		JToolBarHelper::custom("force_password_change","forcepassword","forcepassword","Force Password Change",false);
		JToolBarHelper::custom("copy_emails","copyemail","copyemail","Copy Emails Into Usernames",false);

		// Get data from the model
                $data = JRequest::get( 'post' );
                if(isset($data["search"]) && $data["search"]!=''):
                    $items = & $this->get('Search');
                else: 
                    $items = & $this->get('Data');
                endif;
                
		$this->assignRef('items',$items);
		$this->assignRef('search',$data["search"]);
                
		/* Call the state object */
		$state =& $this->get( 'state' );		
                $lists['order_Dir'] = $state->get( 'filter_order_Dir' );
		$lists['order']     = $state->get( 'filter_order' );
                
		$this->assignRef('pagination',	$this->get("Pagination"));
                
		$this->assignRef( 'lists', $lists );
                
		parent::display($tpl);
	}
}