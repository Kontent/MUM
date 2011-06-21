<?php defined('_JEXEC') or die('Restricted access'); ?>
<form action="index.php" method="post" name="adminForm">
<table>
        <tr>
                <td width="100%">
                        <?php echo JText::_( 'Filter' ); ?>:
                        <input type="text" name="search" id="search" value="<?php echo htmlspecialchars($this->search);?>" class="text_area" onchange="document.adminForm.submit();" />
                        <button onclick="this.form.submit();"><?php echo JText::_( 'Go' ); ?></button>
                        <button onclick="document.getElementById('search').value='';this.form.getElementById('filter_type').value='0';this.form.getElementById('filter_logged').value='0';this.form.submit();"><?php echo JText::_( 'Reset' ); ?></button>
                </td>
                <td nowrap="nowrap">
                </td>
        </tr>
</table>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5" class="title">
				<?php echo JText::_( '#' ); ?>
			</th>
			<th width="20" class="title">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
			</th>	
                        <th class="title">
                                <a title="Click to sort by this column" href="javascript:tableOrdering('name','<?php if($this->lists['order']=="name"){ echo $this->lists['order_Dir']=="asc"?"desc":"asc"; } else echo "desc"; ?>','');">Name<?php if($this->lists['order']=="name") : ?><img alt="" src="images/sort_<?php echo $this->lists['order_Dir']=="asc"?"desc":"asc"; ?>.png"><?php endif; ?></a>
                        </th>
                        <th class="title" >
                                <a title="Click to sort by this column" href="javascript:tableOrdering('username','<?php if($this->lists['order']=="username"){ echo $this->lists['order_Dir']=="asc"?"desc":"asc"; } else echo "desc"; ?>','');">Username<?php if($this->lists['order']=="username") : ?><img alt="" src="images/sort_<?php echo $this->lists['order_Dir']=="asc"?"desc":"asc";  ?>.png"><?php endif; ?></a>
                        </th>
                        <th width="15%" class="title">
                                <a title="Click to sort by this column" href="javascript:tableOrdering('email','<?php if($this->lists['order']=="name"){ echo $this->lists['order_Dir']=="asc"?"desc":"asc"; } else echo "desc"; ?>','');">Email<?php if($this->lists['order']=="email") : ?><img alt="" src="images/sort_<?php echo $this->lists['order_Dir']=="asc"?"desc":"asc"; ?>.png"><?php endif; ?></a>
                        </th>
                        <th width="100" class="title">
                                <?php echo JText::_('Has Password'); ?>
                        </th>
		</tr>
	</thead>
        <tfoot>
                <tr>
                        <td colspan="10">
                                <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                </tr>
        </tfoot>
        <tbody>
	<?php
	$k = 0;
	for ($i=0, $n=count( $this->items ); $i < $n; $i++)	{
		$row = &$this->items[$i];
		$checked 	= JHTML::_('grid.id',   $i, $row->id );
                $link 	= 'index.php?option=com_users&amp;view=user&amp;task=edit&amp;cid[]='. $row->id. '';
		?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $i+1; ?>
			</td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td>
                            <a href="<?php echo $link; ?>"><?php echo $row->name; ?></a>
			</td>
			<td>
				<?php echo $row->username; ?>
			</td>
			<td>
                            <a href="mailto:<?php echo $row->email; ?>"><?php echo $row->email; ?></a>
			</td>
			<td align="center">
                            <?php echo $row->password!=''?'<img src="images/tick.png" width="16" height="16" border="0" alt="" />': ''; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	}
	?>
        </tbody>
	</table>
</div>

<input type="hidden" name="option" value="com_massusermanager" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />

<input type="hidden" name="ce_users" value="" id="ce_users"/>
<input type="hidden" name="ce_email" value="" id="ce_email" />
<input type="hidden" name="ce_overwrite" value="" id="ce_overwrite" />

<input type="hidden" name="crp_users" value="" id="crp_users" />
<input type="hidden" name="crp_overwrite" value="" id="crp_overwrite" />

<input type="hidden" name="fpc_users" value="" id="fpc_users" />

<input type="hidden" name="controller" value="massusermanager" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

<div style="display:none" >
    
    <div id="copy_emails_box" style="padding:20px;" >
        <form>
            <fieldset>
                <legend>Copy Emails to Usernames</legend>
                <label for="ce_users_dropdown" >
                <select name="ce_users_dropdown" id="ce_users_dropdown">
                    <option value="selected_users" >Selected Users</option>
                    <option value="all_users" >All Users</option>
                    <option value="no_usernames" >Only Users with no usernames</option>
                </select>
                </label>
                <br/>
                <label for="ce_email_checkbox" >
                    <input type="checkbox" name="ce_email_checkbox" id="ce_email_checkbox" />
                    <span>Copy entire email?</span>
                </label>
                <br/>
                <label for="ce_overwrite_checkbox" ><input type="checkbox" name="ce_overwrite_checkbox" id="ce_overwrite_checkbox" />
                    <span>Overwrite if value already set?</span>

                </label>
                <br/>
                <br/>
                <input type="button" onclick="submitbutton('copy_emails_form')" value="OK" />
                <input type="button" onclick="SqueezeBox.close()" value="Cancel" />
            </fieldset>
        </form>
    </div>
    
    <div id="create_random_passwords_box" style="padding:20px;" >
        <form>
            <fieldset>
                <legend>Send New Passwords</legend>
                <label for="crp_users_dropdown" >
                <select name="crp_users_dropdown" id="crp_users_dropdown">
                    <option value="selected_users" >Selected Users</option>
                    <option value="all_users" >All Users</option>
                    <option value="no_passwords" >Only Users with no passwords</option>
                </select>
                </label>
                <br/>
                <label for="crp_overwrite_checkbox" ><input type="checkbox" name="crp_overwrite_checkbox" id="crp_overwrite_checkbox" />
                    <span>Overwrite if value already set?</span>

                </label>
                <br/>
                <br/>
                <input type="button" onclick="submitbutton('create_random_passwords_form')" value="OK" />
                <input type="button" onclick="SqueezeBox.close()" value="Cancel" />
            </fieldset>
        </form>
    </div>
    
    <div id="force_password_change_box" style="padding:20px;" >
        <form>
            <fieldset>
                <legend>Force Password Change</legend>
                <label for="fpc_users_dropdown" >
                <select name="fpc_users_dropdown" id="fpc_users_dropdown">
                    <option value="selected_users" >Selected Users</option>
                    <option value="all_users" >All Users</option>
                </select>
                </label>
                <br/>
                <br/>
                <input type="button" onclick="submitbutton('force_password_change_form')" value="OK" />
                <input type="button" onclick="SqueezeBox.close()" value="Cancel" />
            </fieldset>
        </form>
    </div>
    
</div>

<script type="text/javascript" >
function submitbutton(pressbutton) {
 switch(pressbutton){
     
     case "copy_emails":
         SqueezeBox.fromElement(new Element("a",{"rel":"{'adopt':'copy_emails_box','handler':'adopt',size:{x: 400, y: 170}}"}));
     break;
     
     case "copy_emails_form":
         $("ce_users").value=$$("#sbox-content select[name='ce_users_dropdown']").getValue();
         $("ce_email").value=$$("#sbox-content input[name='ce_email_checkbox']").getProperty("checked");
         $("ce_overwrite").value=$$("#sbox-content input[name='ce_overwrite_checkbox']").getProperty("checked");
         SqueezeBox.close();
         document.adminForm.task.value="copy_emails";
         submitform("copy_emails");
     break;
     
     case "force_password_change":
         SqueezeBox.fromElement(new Element("a",{"rel":"{'adopt':'force_password_change_box','handler':'adopt',size:{x: 400, y: 130}}"}));
     break;
     
     case "force_password_change_form":
         $("fpc_users").value=$$("#sbox-content select[name='fpc_users_dropdown']").getValue();
         SqueezeBox.close();
         document.adminForm.task.value="force_password_change";
         submitform("force_password_change");
     break;
     
     case "create_random_passwords":
         SqueezeBox.fromElement(new Element("a",{"rel":"{'adopt':'create_random_passwords_box','handler':'adopt',size:{x: 400, y: 150}}"}));
     break;
     
     case "create_random_passwords_form":
         $("crp_users").value=$$("#sbox-content select[name='crp_users_dropdown']").getValue();
         $("crp_overwrite").value=$$("#sbox-content input[name='crp_overwrite_checkbox']").getProperty("checked");
         SqueezeBox.close();
         document.adminForm.task.value="create_random_passwords";
         submitform("create_random_passwords");
     break;
     
     default:
        document.adminForm.task.value=pressbutton;
        submitform(pressbutton);
     break;
 }
}
</script>
