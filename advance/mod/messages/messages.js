// Global message counter
var messages_selected = 0;

/**
* Function that check the conditions for send the messages action form
*
* @param object select The select object
* @param object actionForm the form object
* @param string msg Message used for the delete confirmation question.
* @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
* @copyright Corporación Somos más - 2007
*/
function submitMessagesAction(select,actionform,msg){
  if(select.options[select.selectedIndex].value!=-1 && select.options[select.selectedIndex].value!='delete' && messages_selected>0){
    actionform.submit();
  }
  else if(select.options[select.selectedIndex].value=='delete' && messages_selected>0){
  	if(confirm(msg)){
  	  actionform.submit();
  	}
  }
}

/**
* Modify the 'messages_select' global variable acording to the check status
* 
* @param object check The checkbox object
* @author Diego Andrés Ramírez Aragón <diego@somosmas.org>
* @copyright Corporación Somos más - 2007
*/
function mark(check){
  if(check.checked){
    messages_selected++;
  }
  else{
    messages_selected--;
  }
}