/*
 * Created on 2008/09/23
 * Author: Thao Tran
 * util functions
 **/
 
var strAlphaNum = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";

// Response to keypress event of a input field, accept alpha numeric keys only
/**
  * input alphanumeric
  * @return true: ok; false: not ok
  * **/
function inputAlphaNumericOnly(evt)
{
    var key = (window.Event) ? evt.which : evt.keyCode;    
    var chr = String.fromCharCode(key);   
    if (strAlphaNum.indexOf(chr)==-1 && chr.charCodeAt(0) != 13)
    {
		(window.Event) ? evt.which : evt.keyCode = 0;
		return false;
	}
	
	return true;
}

/**
 * Removes leading whitespaces
 * @param: value - string
 * @return: value - string - after remove leading whitespaces
 */
function LTrim( value )
{
	var re = /\s*((\S+\s*)*)/;
	return value.replace(re, "");
}

/**
 * Removes ending whitespaces
 * @param: value - string
 * @return: value - string - after remove ending whitespaces
 */
function RTrim( value )
{
	var re = /((\s*\S+)*)\s*/;
	return value.replace(re, "");
}

/**
 * Removes leading and ending whitespaces
 * @param: value - string
 * @return: value - string - after remove leading and ending whitespaces
 **/
function Trim(value) {
	a = value.replace(/^\s+/, '');
	return a.replace(/\s+$/, '');
};


/**
 * Check whether a string is null
 * @param - string to be check
 * @return - true if it's null, else it is not null
 * **/
function isNull(sStr)
{
    if ((sStr == null) || (Trim(sStr) == ''))
    {
        return true;
	}
    else
    {
        return false;
	}
}

/**
 * disable an element
 * @return true: ok; false: not ok
 **/
function disable(IsReset)
{
	if (IsReset)
	{
		window.event.keyCode = 0;
		return false;
	}
	return true;
}

/**
 * check sign selection for selection box
 * @param - cboField, value
 **/
function cboSelected(cboField, value)
{
	for(var i=0;i<cboField.length;i++)
	{
		if(cboField[i].value==value)
		{
			cboField.selectedIndex=i;
		}
	}
}

/**
* check sign selection for chekcbox or radio button
* @param - objField, value
* **/
function optSelected(objField, value)
{
	for(var i=0;i<objField.length;i++)
	{
    	if(objField[i].value==value)
    	{
        	objField[i].checked=true;
        }
	}
}

/**
  * go to next page
  * @param: form, action name
  * @return none
  * **/
function doLink(frm, sAction)
{
	frm.action = sAction;
	frm.submit();
}

/**
  * selected all check box with another field
  * @param: form, fieldName
  * @return nonefield
  * **/
function checkAll(frm, Field)
{
	var iTmp;
	for (var i=0;i<frm.elements.length;i++)
	{
		var e=frm.elements[i];
		if (e.type=='checkbox' && e.name==Field.name)
		{ 
			iTmp=i;				
		}
		if (e.name!=Field.name && i>=iTmp && !e.disabled)
		{
			e.checked=Field.checked;
		}
	}
}