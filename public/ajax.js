function add_checked(){
if (document.getElementById('alladd_checked').checked){
         var cb = document.getElementById('add_form').getElementsByTagName('input');
	
		  for (var i = 0; i < cb.length; i++) {
			  var sin=cb[i].id;
if(sin=="addcheckbox" || sin=="alladd_checked"){
cb[i].checked= true;
}else{
cb[i].checked= false;	
}

}
}else{
         var cb = document.getElementById('add_form').getElementsByTagName('input');
	
		  for (var i = 0; i < cb.length; i++) {
			  var sin=cb[i].id;
if(sin=="addcheckbox" || sin=="alladd_checked"){
cb[i].checked= false;
}else{

}

}
}
}

function notadd_checked(){
if (document.getElementById('allnotadd_checked').checked){
         var cb = document.getElementById('add_form').getElementsByTagName('input');
	
		  for (var i = 0; i < cb.length; i++) {
			  var sin=cb[i].id;
if(sin=="notaddcheckbox" || sin=="allnotadd_checked"){
cb[i].checked= true;
}else{
cb[i].checked= false;	
}

}
}else{
         var cb = document.getElementById('add_form').getElementsByTagName('input');
	
		  for (var i = 0; i < cb.length; i++) {
			  var sin=cb[i].id;
if(sin=="notaddcheckbox" || sin=="allnotadd_checked"){
cb[i].checked= false;
}else{

}

}
}
}