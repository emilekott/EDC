function addEvent(o,e,f){
	switch(e){
		case 'click':{
			o.kfm_click=f;
			f=function(e){
				if(e.button==1)this.kfm_click(e);
			};
			break;
		}
		case 'contextmenu':{
			e='mousedown';
			o.kfm_contextmenu=f;
			f=function(e){
				if(e.button==2){
					e.preventDefault();
					this.kfm_contextmenu(e);
				}
			};
		}
	}
	o.addEventListener(e,f,false);
}
