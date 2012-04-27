FCKConfig.SkinPath = FCKConfig.BasePath + 'skins/office2003/' ;

FCKConfig.ToolbarSets["Default"] = [
	['Source','-','Save','NewPage','Preview','-','Templates'],
	['Cut','Copy','Paste','PasteText','PasteWord','-','Print','SpellCheck'],
	['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	['-','About'],
	'/',
	['Bold','Italic','Underline','StrikeThrough','-','Subscript','Superscript'],
	['OrderedList','UnorderedList','-','Outdent','Indent'],
	['JustifyLeft','JustifyCenter','JustifyRight','JustifyFull'],
	['Form','Checkbox','Radio','TextField','Textarea','Select','Button','ImageButton','HiddenField'],
	'/',
	['Link','Unlink','Anchor'],
	['Image','Flash','Table','Rule','Smiley','SpecialChar','PageBreak','-','wpMore'],
	'/',
	['TextColor','BGColor'],
	['Style','FontFormat','FontName','FontSize']
] ;

FCKConfig.SpellChecker = 'ieSpell' ;	// 'ieSpell' | 'SpellerPages'
FCKConfig.Plugins.Add('kfm');
FCKConfig.Plugins.Add('wpMore');