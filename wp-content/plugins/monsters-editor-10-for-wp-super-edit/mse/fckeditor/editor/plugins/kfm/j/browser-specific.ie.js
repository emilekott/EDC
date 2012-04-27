// see ../license.txt for licensing
function kfm_cancelEvent(e,c){
	e.cancelBubble=true;
	if(c)e.returnValue=false; // contextmenu
}

function clearSelections(){
	document.selection.empty();
}
function getEventTarget(e,tagName){
	return window.event.srcElement;
}
function getWindowScrollAt(){
	var d=document.body;
	if(d.scrollLeft||d.scrollTop)return {x:d.scrollLeft,y:d.scrollTop};
	d=document.documentElement;
	return {x:d.scrollLeft,y:d.scrollTop};
}
function newEl(t,id,cn,chld,vals,css){
	if(t=='iframe')return newEl('<iframe name="'+id+'" src="empty"></iframe>');
	var el=document.createElement(t);
	if(id){
		el.id=id;
		el.name=id;
	}
	kfm_addMethods(el);
	if(cn)el.setClass(cn);
	if(chld)el.addEl(chld);
	if(vals)X(el,vals);
	if(css)el.setCss(css);
	return el;
}
function newForm(action,method,enctype,target){
	return newEl('<form action="'+action+'" method="'+method+'" enctype="'+enctype+'" target="'+target+'"></form>');
}
function removeEvent(o,t,f){
	if(!o)return;
	if(!o[t+f])return;
	o.detachEvent('on'+t,o['e'+t+f]);
	o[t+f]=null;
}
function setFloat(e,f){
	e.style.styleFloat=f;
}
function setOpacity(e,o){
	e.style.filter='alpha(opacity='+(o*100)+')';
}
