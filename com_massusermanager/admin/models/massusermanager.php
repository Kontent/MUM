<?php
/**
 * Hellos Model for Hello World Component
 * 
 * @package    Joomla.Tutorials
 * @subpackage Components
 * @link http://docs.joomla.org/Developing_a_Model-View-Controller_Component_-_Part_4
 * @license		GNU/GPL
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.user.helper');

/**
 * Hello Model
 *
 * @package    Joomla.Tutorials
 * @subpackage Components
 */
class MassUserManagerModelMassUserManager extends JModel
{
	/**
	 * Hellos data array
	 *
	 * @var array
	 */
	var $_data;
        public $_pagination;
	var $_message='';
        
        function __construct()
        {
                parent::__construct();

                global $mainframe, $option;

                $filter_order     = $mainframe->getUserStateFromRequest(  $option.'filter_order', 'filter_order', 'id', 'cmd' );
                $filter_order_Dir = $mainframe->getUserStateFromRequest( $option.'filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );

                $this->setState('filter_order', $filter_order);
                $this->setState('filter_order_Dir', $filter_order_Dir);
        }

	/**
	 * Returns the query
	 * @return string The query to be used to retrieve the rows from the database
	 */
	function _buildQuery()
	{
                global $mainframe,$options;
            
                $data = JRequest::get( 'post' );
                
                if(isset($data["search"]) && $data["search"]!=''):
                    $query = ' SELECT * '
                            . ' FROM #__users as a WHERE userType = "Registered" '
                            . ' AND ( name LIKE "%'.  mysql_real_escape_string($search).'%" '
                            . ' OR email LIKE "%'.  mysql_real_escape_string($search).'%" ' 
                            . ' OR username LIKE "%'.  mysql_real_escape_string($search).'%" ) ' 
                    ;
                else :
                    $query = ' SELECT * '
                            . ' FROM #__users as a WHERE userType = "Registered" '
                    ;
                endif;
                
                $query.= ' ORDER BY '.$this->getState("filter_order").' '.$this->getState("filter_order_Dir");
                    
		$total = count($this->_getList($query));
                
		$limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
		$limitstart = $mainframe->getUserStateFromRequest( $options.'.limitstart', 'limitstart', 0, 'int' );
                
		jimport('joomla.html.pagination');
                $this->_pagination = new JPagination( $total, $limitstart, $limit );
		
		return $query;
	}

	/**
	 * Retrieves pagination data
	 * @return array Array of objects containing the data from the database
	 */
	function getPagination()
	{

		return $this->_pagination;
	}

	/**
	 * Retrieves the data
	 * @return array Array of objects containing the data from the database
	 */
	function getData()
	{
                global $mainframe,$options;
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
                        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
                        $limitstart = $mainframe->getUserStateFromRequest( $options.'.limitstart', 'limitstart', 0, 'int' );
			$this->_data = $this->_getList( $query , $limitstart , $limit);
		}

		return $this->_data;
	}
        

	/**
	 * Retrieves search data
	 * @return array Array of objects containing the data from the database
	 */
	function getSearch()
	{
                global $mainframe,$options;
		// Lets load the data if it doesn't already exist
		if (empty( $this->_data ))
		{
			$query = $this->_buildQuery();
                        $limit = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
                        $limitstart = $mainframe->getUserStateFromRequest( $options.'.limitstart', 'limitstart', 0, 'int' );
			$this->_data = $this->_getList( $query , $limitstart , $limit);
		
		}

		return $this->_data;
	}
        
        function copy_emails(){

		$data = JRequest::get( 'post' );
		$db = &JFactory::getDBO();
		$ce_users = $data["ce_users"];
                
                $i = 0; // HOW MANY ENTRIES WAS SAVED
               
                switch($ce_users):
                    
                    case "selected_users":
                       
                        foreach($data["cid"] as $cid):

                            $user = JFactory::getUser($cid);

                            if($data["ce_overwrite"]=="true" || ($data["ce_overwrite"]=="false" && $user->username=='')){

                                $username = $user->email;

                                if($data["ce_email"]=="false"){
                                    $username = explode("@",$username);
                                    $username = $username[0];
                                    $username = $this->get_unique_name($username,(int)$user->id);
                                }
                                
                                $db->setQuery( "UPDATE #__users SET username = ".$db->Quote($username)." WHERE id = ". (int) $user->id);
                                $db->query();


                                $i+=1;
                            }

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                    
                    break;
                    
                    case "all_users":
                        
                        foreach($this->getData() as $item):
                                
                            $user = JFactory::getUser($item->id);

                            if($data["ce_overwrite"]=="true" || ($data["ce_overwrite"]=="false" && $user->username=='')){

                                $username = $user->email;

                                if($data["ce_email"]=="false"){
                                    $username = explode("@",$username);
                                    $username = $username[0];
                                    $username = $this->get_unique_name($username,(int)$user->id);
                                }
                                
                                $db->setQuery( "UPDATE #__users SET username = ".$db->Quote($username)." WHERE id = ". (int) $user->id);
                                $db->query();

                                $i+=1;
                            }

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                        
                    break;
                    
                    case "no_usernames":
                        
                        foreach($this->getData() as $item):
                                
                            $user = JFactory::getUser($item->id);

                            if($user->username==''){

                                $username = $user->email;

                                if($data["ce_email"]=="false"){
                                    $username = explode("@",$username);
                                    $username = $username[0];
                                    $username = $this->get_unique_name($username,(int)$user->id);
                                }
                                

                                $db->setQuery( "UPDATE #__users SET username = ".$db->Quote($username)." WHERE id = ". (int) $user->id);
                                $db->query();

                                $i+=1;
                            }

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                        
                    break;
                
                endswitch;

		return true;
                
        }
        
        function create_random_passwords(){
            
		$data = JRequest::get( 'post' );
		$db = &JFactory::getDBO();
		$crp_users = $data["crp_users"];
                
                $i = 0; // HOW MANY ENTRIES WAS SAVED
                
                switch($crp_users):
                    
                    case "selected_users":
                        
                        if(isset($data["cid"]) && is_array($data["cid"])){
                            
                            foreach($data["cid"] as $cid):
                                
                                $user = JFactory::getUser($cid);
                                
                                if($data["crp_overwrite"]=="true" || ($data["crp_overwrite"]=="false" && $user->password=='')){
                                    
                                    list($password,$clear_password) = $this->get_password();

                                    $db->setQuery( "UPDATE #__users SET password = ".$db->Quote($password)." WHERE id = ". (int) $user->id);
                                    $db->query();
                                    
                                    $this->send_crp_email($user->username, $clear_password ,$user->email );
                                    
                                    $i+=1;
                                }
                            
                            endforeach;
                        
                        }
                        
                        $this->_message=$i;
                        
                        return true;
                    
                    break;
                    
                    case "all_users":
                        
                        foreach($this->getData() as $item):

                            $user = JFactory::getUser($item->id);

                            if($data["crp_overwrite"]=="true" || ($data["crp_overwrite"]=="false" && $user->password=='')){

                                list($password,$clear_password) = $this->get_password();

                                $db->setQuery( "UPDATE #__users SET password = ".$db->Quote($password)." WHERE id = ". (int) $user->id);
                                $db->query();
                                    
                                $this->send_crp_email($user->username, $clear_password ,$user->email );

                                $i+=1;
                            }

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                        
                    break;
                    
                    case "no_passwords":
                        
                        foreach($this->getData() as $item):

                            $user = JFactory::getUser($item->id);

                            if($user->password==''){
                                
                                list($password,$clear_password) = $this->get_password();

                                $db->setQuery( "UPDATE #__users SET password = '".$db->Quote($password)."' WHERE id = ". (int) $user->id);
                                $db->query();
                                $this->send_crp_email($user->username, $clear_password ,$user->email );

                                $i+=1;
                            }

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                        
                    break;
                
                endswitch;

		return true;
                
        }
        function force_password_change(){
            
		$data = JRequest::get( 'post' );
		$db = &JFactory::getDBO();
		$fpc_users = $data["fpc_users"];
                
                $i = 0; // HOW MANY ENTRIES WAS SAVED
                
                switch($fpc_users):
                    
                    case "selected_users":
                        
                        if(isset($data["cid"]) && is_array($data["cid"])){
                            
                            foreach($data["cid"] as $cid):
                                
                                $user = JFactory::getUser($cid);

                                $db->setQuery( "UPDATE #__users SET lastvisitDate = '0000-00-00 00:00:01' WHERE id = ". (int) $user->id);
                                $db->query();

                                $i+=1;
                            
                            endforeach;
                        
                        }
                        
                        $this->_message=$i;
                        
                        return true;
                    
                    break;
                    
                    case "all_users":
                        
                        foreach($this->getData() as $item):

                            $user = JFactory::getUser($item->id);

                            $db->setQuery( "UPDATE #__users SET lastvisitDate = '0000-00-00 00:00:01' WHERE id = ". (int) $user->id);
                            $db->query();

                            $i+=1;

                        endforeach;
                        
                        $this->_message=$i;
                        
                        return true;
                        
                    break;
                
                endswitch;

		return true;
                
        }
        
        private function get_unique_name($string,$id){
            
            $db = &JFactory::getDBO();
            
            $db->setQuery("SELECT COUNT(id) FROM #__users WHERE id <> $id AND username = '".mysql_real_escape_string($string)."' ");
            $db->query();
            $result = $db->loadResult();
            
            
            if($result>0){
                
                $new_string = $string;
                
                $i=1;
                
                while($result>0):
                    
                   $new_string=$string.$i;
                
                    $db->setQuery("SELECT count(id) as num FROM #__users WHERE id <> $id AND username = '".mysql_real_escape_string($new_string)."' ");
                    $db->query();
                    $result = $db->loadResult();
                    
                    $i+=1;
                    
                endwhile;
                
                $string = $new_string;
            }
          
            return $string;
            
            
        }
        
        private function send_fpc_email($username,$password,$email){
                      
            // Set the e-mail parameters
            $config = &JFactory::getConfig();
            $sitename = $config->getValue('sitename');
            $from = $config->getValue('mailfrom');
            $fromname = $config->getValue('fromname');
            $toemail = $email;
            $subject = JText::sprintf('FORCE_PASSWORD_CHANGE_EMAIL_SUBJECT', $sitename);
            $body = JText::sprintf('FORCE_PASSWORD_CHANGE_EMAIL_BODY', $username );

            JUtility::sendMail($from, $fromname, $toemail, $subject, $body);
           
        }
        
        private function send_crp_email($username,$password,$email){
                      
            // Set the e-mail parameters
            $config = &JFactory::getConfig();
            $sitename = $config->getValue('sitename');
            $from = $config->getValue('mailfrom');
            $fromname = $config->getValue('fromname');
            $toemail = $email;
            $subject = JText::sprintf('CREATE_RANDOM_PASSWORD_EMAIL_SUBJECT', $sitename);
            $body = JText::sprintf('CREATE_RANDOM_PASSWORD_EMAIL_BODY', $username, $password  );

            JUtility::sendMail($from, $fromname, $toemail, $subject, $body);
           
        }
        
        private function get_random_string($limit){
            
            $characters ="0123456789qwertyuiopasdfghjklzxcvbnm";
            
            $string = '';
            for($i=0;$i<$limit;$i++){
                
                $rand = floor(rand(0, strlen($characters)));
                $string.=$characters{$rand};
                
            }
            
            return $string;
            
        }
        
        function get_password(){
            $password = $this->get_random_string(10);
            $salt = JUserHelper::genRandomPassword(32);
            $crypt = JUserHelper::getCryptedPassword($password, $salt);
            $new_password = $crypt.':'.$salt;
            
            return array(
                $new_password,
                $password
            );
            
        }
}