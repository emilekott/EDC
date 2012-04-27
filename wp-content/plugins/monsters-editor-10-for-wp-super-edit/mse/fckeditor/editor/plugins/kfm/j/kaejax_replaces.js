// see license.txt for licensing
var kfm_kaejax_replaces={'([89A-F][A-Z0-9])':'%u00$1','22':'"','2C':',','3A':':','5B':'[','5D':']','7B':'{','7D':'}'};
for(var a in kfm_kaejax_replaces){
	kfm_kaejax_replaces[kfm_kaejax_replaces[a]]=eval('/%'+a+'/g');
	delete kfm_kaejax_replaces[a];
}
function kfm_sanitise_ajax(d){
	var r=kfm_kaejax_replaces;
	for(var a in r)d=d.replace(r[a],a);
	return d;
}
