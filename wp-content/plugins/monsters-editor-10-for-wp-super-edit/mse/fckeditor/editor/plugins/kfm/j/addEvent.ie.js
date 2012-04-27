function addEvent(o,t,f){
	o['e'+t+f]=f;
	o[t+f]=function(){o['e'+t+f](window.event)}
	o.attachEvent('on'+t,o[t+f]);
}
