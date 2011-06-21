<?php
/**
 * Hellos View for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

JHTML::_('behavior.mootools');
JHTML::_('behavior.modal');
JHTML::_('stylesheet', 'massusermanager.css', 'administrator/components/com_massusermanager/assets/css/');
/**
 * Hellos View
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MassUserManagerViewMassUserManager extends JView
{
	/**
	 * Hellos view display method
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