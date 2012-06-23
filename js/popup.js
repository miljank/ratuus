function popup(url){
	window.open(
		url,
		"PopUp",
		"width=400,height=300,toolbar=0,menubar=0"
	 );
	void( 0 );
}

var preloaded = new Array();
	function preload_images() {
		for (var i = 0; i < arguments.length; i++){
		preloaded[i] = document.createElement('img');
		preloaded[i].setAttribute('src',arguments[i]);
	};
};
preload_images(
	'templates/ratuus/img/m1h.png',
	'templates/ratuus/img/m2h.png',
	'templates/ratuus/img/m3h.png',
	'templates/ratuus/img/m4h.png',
	'templates/ratuus/img/m4h.png',
	'templates/ratuus/img/fi2.gif',
	'templates/ratuus/img/fi4.gif'
);

function confirm(msg,callback) {
	$('#confirm').jqmShow().find('p.jqmConfirmMsg').html(msg).end().find(':submit:visible').click(function() {
		if(this.value == 'yes') (typeof callback == 'string') ? window.location.href = callback : callback();
		$('#confirm').jqmHide();
	});
}


$().ready(function() {
	$('#confirm').jqm({overlay: 88, modal: true, trigger: false});
	// trigger a confirm whenever links of class alert are pressed.
	$('a.confirm').click(function() { 
		confirm('You are about to delete '+this.name+'.',this.href); 
		return false;
	});
});
