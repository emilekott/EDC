// see license.txt for licensing
function kfm_clearMessage(message){
	$('message').setCss('textDecoration:none').innerHTML=message;
	setTimeout('kfm_hideMessage()',3000);
}
function kfm_setMessage(message){
	var m=$('message').setCss('display:block;textDecoration:blink').innerHTML=message;
}
