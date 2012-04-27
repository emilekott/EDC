// see ../license.txt for licensing
function kfm_addToSelection(id){
	if(!id)return;
	selectedFiles.push(id);
	$('kfm_file_icon_'+id).addClass('selected');
	if(kfm_log_level>0)kfm_log(kfm_lang.FileSelected(id));
	kfm_selectionCheck();
}
function kfm_chooseFile(e,o){
	var el=(o?e:getEventTarget(e)).kfm_attributes;
	x_kfm_getFileUrl(el.id,function(url){
		if(kfm_file_handler=='return'||kfm_file_handler=='fckeditor'){
			if(!el.width)window.opener.SetUrl(url);
			else window.opener.SetUrl(url.replace(/([^:]\/)\//g,'$1'),0,0,$('kfm_file_icon_'+el.id).kfm_attributes.caption);
			window.close();
		}
		else if(kfm_file_handler=='download'){
			if(/get.php/.test(url))url+='&forcedownload=1';
			document.location=url;
		}
	});
}
function kfm_file_drag(e){
	if(!window.dragType||window.dragType!=1)return;
	clearSelections();
	var m=getMouseAt(e);
	var w=drag_wrapper.offsetWidth,h=drag_wrapper.offsetHeight,ws=getWindowSize();
	var x=(w+m.x>ws.x-16)?ws.x-w:m.x+16;
	var y=(h+m.y>ws.y)?ws.y-h:m.y;
	if(x<0)x=0;
	if(y<0)y=0;
	window.drag_wrapper.setCss('display:block;left:'+x+'px;top:'+y+'px');
}
function kfm_file_dragFinish(e){
	clearTimeout(window.dragTrigger);
	if(!window.dragType||window.dragType!=1)return;
	window.dragType=0;
	delEl(window.drag_wrapper);
	window.drag_wrapper=null;
	removeEvent(document,'mousemove',kfm_file_drag);
	removeEvent(document,'mouseup',kfm_file_dragFinish);
	if(kfm_directory_over)dir_over=kfm_directory_over;
	else{ // workaround for Firefox which seems to have trouble with onmouseover for the directories while dragging
		var a=kfm_getContainer(getMouseAt(e),getElsWithClass('kfm_directory_link','DIV'));
		dir_over=a?a.node_id:'.';
	}
	if(dir_over=='.'||dir_over==kfm_cwd_id)return;
	{ // build context menu for "copy/move"
		var links=[];
		links.push(['x_kfm_copyFiles(['+selectedFiles.join(',')+'],'+dir_over+',kfm_alert);kfm_selectNone()','copy files']);
		links.push(['x_kfm_moveFiles(['+selectedFiles.join(',')+'],'+dir_over+',kfm_refreshFiles);kfm_selectNone()','move files',0,!kfm_vars.permissions.move]);
		kfm_createContextMenu(getMouseAt(getEvent(e)),links);
	}
}
function kfm_file_dragStart(filename){
	if(!kfm_isFileSelected(filename))kfm_addToSelection(filename);
	if(!selectedFiles.length)return;
	window.dragType=1;
	var w=getWindowSize();
	window.drag_wrapper=newEl('div','kfm_drag_wrapper',0,0,0,'display:none;opacity:.7');
	for(var i=0;i<10&&i<selectedFiles.length;++i)window.drag_wrapper.addEl([$('kfm_file_icon_'+selectedFiles[i]).kfm_attributes.name,newEl('br')]);
	if(selectedFiles.length>10)window.drag_wrapper.addEl(newEl('i',0,0,kfm_lang.AndNMore(selectedFiles.length-10)));
	document.body.addEl(window.drag_wrapper);
	addEvent(document,'mousemove',kfm_file_drag);
}
function kfm_isFileSelected(filename){
	return kfm_inArray(filename,selectedFiles);
}
function kfm_removeFromSelection(id){
	var i;
	for(i in selectedFiles){
		if(selectedFiles[i]==id){
			var el=$('kfm_file_icon_'+id);
			if(el)el.delClass('selected');
			kfm_selectionCheck();
			return selectedFiles.splice(i,1);
		}
	}
}
function kfm_selectAll(){
	kfm_selectNone();
	var a,b=$('kfm_right_column').fileids;
	for(a in b)kfm_addToSelection(b[a]);
}
function kfm_selectInvert(){
	var a,b=$('kfm_right_column').fileids;
	for(a in b)if(kfm_isFileSelected(b[a]))kfm_removeFromSelection(b[a]);
	else kfm_addToSelection(b[a]);
}
function kfm_selectNone(){
	if(kfm_lastClicked)$('kfm_file_icon_'+kfm_lastClicked).delClass('last_clicked');
	for(var i=selectedFiles.length;i>-1;--i)kfm_removeFromSelection(selectedFiles[i]);
	kfm_selectionCheck();
}
function kfm_selectionCheck(){
	if(selectedFiles.length==1){
		getElsWithClass('kfm_panel_body','DIV',$('kfm_file_details_panel')).innerHTML='loading';
		kfm_run_delayed('file_details','if(selectedFiles.length==1)x_kfm_getFileDetails(selectedFiles[0],kfm_showFileDetails);');
	}
	else kfm_run_delayed('file_details','if(!selectedFiles.length)kfm_showFileDetails();');
}
function kfm_selection_drag(e){
	if(!window.dragType||window.dragType!=2||!window.drag_wrapper)return;
	clearSelections();
	var p1=getMouseAt(e),p2=window.drag_wrapper.orig;
	var x1=p1.x>p2.x?p2.x:p1.x;
	var x2=p2.x>p1.x?p2.x:p1.x;
	var y1=p1.y>p2.y?p2.y:p1.y;
	var y2=p2.y>p1.y?p2.y:p1.y;
	window.drag_wrapper.setCss('display:block;left:'+x1+'px;top:'+y1+'px;width:'+(x2-x1)+'px;height:'+(y2-y1)+'px;zIndex:4');
}
function kfm_selection_dragFinish(e){
	clearTimeout(window.dragSelectionTrigger);
	if(!window.dragType||window.dragType!=2||!window.drag_wrapper)return;
	var right_column=$('kfm_right_column'),p1=getMouseAt(e),p2=window.drag_wrapper.orig,offset=right_column.scrollTop;
	var x1=p1.x>p2.x?p2.x:p1.x,x2=p2.x>p1.x?p2.x:p1.x,y1=p1.y>p2.y?p2.y:p1.y,y2=p2.y>p1.y?p2.y:p1.y;
	if(offset){
		y1+=offset;
		y2+=offset;
	}
	setTimeout('window.dragType=0;',1); // pause needed for IE
	delEl(window.drag_wrapper);
	window.drag_wrapper=null;
	removeEvent(document,'mousemove',kfm_selection_drag);
	removeEvent(document,'mouseup',kfm_selection_dragFinish);
	var fileids=right_column.fileids;
	kfm_selectNone();
	for(var i = 0; i<fileids.length; i++)
	{
	 	var curIcon = $('kfm_file_icon_'+fileids[i]);
		var curTop = getOffset(curIcon,'Top');
		var curLeft = getOffset(curIcon,'Left');
		var curBottom = curTop + curIcon.offsetHeight;
		var curRight = curLeft + curIcon.offsetWidth;
		
		if (curRight > x1 && curLeft < x2)
		{
			if (curBottom > y1 && curTop < y2)
			{
				kfm_addToSelection(fileids[i]);
			}
		}
	}
	
	
	/*
	else{
		for(var i=1;$('kfm_file_icon_'+fileids[i]).offsetTop==firstTop;++i);
		X(icons,{
			iconsPerLine:i,
			marginX:(getOffset($('kfm_file_icon_'+fileids[i-1]),'Left')-firstLeft-(lastfile.offsetWidth*(i-1)))/((i-1)*2),
			marginY:(getOffset($('kfm_file_icon_'+fileids[i]),'Top')-firstTop-lastHeight)/2
		});
	}
	var iw=icons.width+icons.marginX*2;
	var ih=icons.height+icons.marginY*2;
	var leftMost=Math.floor((x1-firstLeft+icons.marginX*2)/iw);
	var topMost=Math.floor((y1-firstTop+icons.marginY*2)/ih);
	var columns=Math.ceil((x2-firstLeft)/iw)-leftMost;
	var rows=Math.ceil((y2-firstTop)/ih)-topMost;
	if(!columns&&!rows)return;
	if(leftMost<0)leftMost=0;
	if(topMost<0)topMost=0;
	if(!columns)columns=1;
	if(!rows)rows=1;
	for(var y=topMost;y<topMost+rows;++y){
		var yi=y*icons.iconsPerLine;
		if(yi>numfiles)break;
		for(var x=leftMost;x<leftMost+columns;++x){
			if(yi+x>numfiles)break;
			kfm_addToSelection(fileids[yi+x]);
		}
	}
	*/
	kfm_selectionCheck();
}
function kfm_selection_dragStart(e){
	if(window.dragType)return;
	if (window.mouseAt.x > $('kfm_right_column').scrollWidth + $('kfm_left_column').scrollWidth) return;
	window.dragType=2;
	var w=getWindowSize();
	addEvent(document,'mouseup',kfm_selection_dragFinish);
	window.drag_wrapper=newEl('div','kfm_selection_drag_wrapper',0,0,0,'display:none;opacity:.7');
	window.drag_wrapper.orig=window.mouseAt;
	document.body.addEl(window.drag_wrapper);
	addEvent(document,'mousemove',kfm_selection_drag);
}
function kfm_shiftFileSelectionLR(dir){
	if(selectedFiles.length>1)return;
	var na=$('kfm_right_column').fileids,a=0,ns=na.length;
	if(selectedFiles.length){
		for(;a<ns;++a)if(na[a]==selectedFiles[0])break;
		if(dir>0){if(a==ns-1)a=-1}
		else if(!a)a=ns;
	}
	else a=dir>0?-1:ns;
	kfm_selectSingleFile(na[a+dir]);
}
function kfm_shiftFileSelectionUD(dir){
	if(selectedFiles.length>1)return;
	var na=$('kfm_right_column').fileids,a=0,ns=na.length,icons_per_line=0,topOffset=$('kfm_file_icon_'+na[0]).offsetTop;
	if(selectedFiles.length){
		if(topOffset==$('kfm_file_icon_'+na[ns-1]).offsetTop)return; // only one line of icons
		for(;$('kfm_file_icon_'+na[icons_per_line]).offsetTop==topOffset;++icons_per_line);
		for(;a<ns;++a)if(na[a]==selectedFiles[0])break; // what is the selected file
		a+=icons_per_line*dir;
		if(a>=ns)a=ns-1;
		if(a<0)a=0;
	}
	else a=dir>0?0:ns-1;
	kfm_selectSingleFile(na[a]);
}
function kfm_toggleSelectedFile(e){
	kfm_cancelEvent(e);
	var el=getEventTarget(e),id=el.kfm_attributes.id;
	if(kfm_lastClicked&&e.shiftKey){
		clearSelections(e);
		kfm_selectNone();
		var a=$('kfm_right_column').fileids,b,c,d;
		for(b in a){
			if(a[b]==kfm_lastClicked)c=parseInt(b);
			if(a[b]==id)d=parseInt(b);
		}
		if(c>d){
			b=c;
			c=d;
			d=b;
		}
		for(;c<=d;++c)kfm_addToSelection(a[c]);
	}
	else{
		if(kfm_isFileSelected(id)){
			if(!e.ctrlKey)kfm_selectNone();
			else kfm_removeFromSelection(id);
		}
		else{
			if(!e.ctrlKey&&!e.metaKey)kfm_selectNone();
			kfm_addToSelection(id);
		}
	}
	if(kfm_lastClicked)$('kfm_file_icon_'+kfm_lastClicked).delClass('last_clicked');
	kfm_lastClicked=id;
	$('kfm_file_icon_'+id).addClass('last_clicked');
}
function kfm_selectSingleFile(id){
	kfm_selectNone();
	kfm_addToSelection(id);
	var panel=$('kfm_right_column'),el=$('kfm_file_icon_'+id);
	var offset=panel.scrollTop,panelHeight=panel.offsetHeight,elTop=getOffset(el,'Top'),elHeight=el.offsetHeight;
	if(elTop+elHeight-offset>panelHeight)panel.scrollTop=elTop-panelHeight+elHeight;
	else if(elTop<offset)panel.scrollTop=elTop;
}
