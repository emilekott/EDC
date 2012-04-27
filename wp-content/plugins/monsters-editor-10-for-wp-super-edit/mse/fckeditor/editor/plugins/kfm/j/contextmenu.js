// see license.txt for licensing
function kfm_closeContextMenu(){
	delEl(contextmenu);
	contextmenu=null;
}
function kfm_contextmenuinit(){
	addEvent(getWindow(),'click',function(e){
		if(!contextmenu)return;
		var c=contextmenu,m=getMouseAt(e);
		var l=c.offsetLeft,t=c.offsetTop;
		if(m.x<l||m.x>l+c.offsetWidth||m.y<t||m.y>t+c.offsetHeight)kfm_closeContextMenu();
	});
	getWindow().oncontextmenu=function(e){
		e=getEvent(e);
		kfm_cancelEvent(e,1);
	}
}
function kfm_createContextMenu(m,links){
	if(!window.contextmenu_loading)kfm_closeContextMenu();
	if(!contextmenu){
		window.contextmenu=newEl('table',0,'contextmenu',0,{
			addLink:function(href,text,icon,disabled){
				var row=this.addRow();
				if(disabled){
					row.addClass('disabled');
					href='';
				}
				var link=(href!='kfm_0')?newLink('javascript:kfm_closeContextMenu();'+href,text):text;
				row.addCell(0,0,(icon?newImg('themes/'+kfm_theme+'/icons/'+icon+'.png'):''),'kfm_contextmenu_iconCell');
				row.addCell(1,0,link);
			}
		},'left:'+m.x+'px;top:'+m.y+'px');
		window.contextmenu_loading=setTimeout('window.contextmenu_loading=null',1);
		document.body.addEl(contextmenu);
	}
	else{
		var col=contextmenu.addRow().addCell();
		col.colSpan=2;
		col.addEl(newEl('hr'));
	}
	var rows=contextmenu.rows.length;
	for(var i=0;i<links.length;++i)if(links[i][1])contextmenu.addLink(links[i][0],links[i][1],links[i][2],links[i][3]);
	var w=contextmenu.offsetWidth,h=contextmenu.offsetHeight,ws=getWindowSize();
	if(h+m.y>ws.y)contextmenu.style.top=(ws.y-h)+'px';
	if(w+m.x>ws.x)contextmenu.style.left=(m.x-w)+'px';
}
