<!--

function gbid(id) {
	return document.getElementById(id);
}

function maskpass(chk, e) {
	if (document.getElementById(chk).checked == true) {
		gbid(e).setAttribute('type','text');
	} else {
		gbid(e).setAttribute('type','password');
	}
}

function checkall(n, chk) {
	var e = document.getElementsByName(n+'[]');
	var l = e.length;
	if (gbid(chk).checked) {
		var state = true;
	} else {
		var state = false;
	}
	for (var i = 0; i < l; i++) {
		e[i].checked = state;
	}
}

function confirmandgo(msg, url) {
	var answer = confirm(msg)
	if (answer){
		window.location = url;
	} else{
		return false;
	}
}

function dropandgo(e) {
	document.location = gbid(e).options[gbid(e).selectedIndex].value;
}

function openiv(isrc,ez,sz) {
	if (sz=='') {
		var sz = 'sm_';
	} 
	gbid('iv_img').src = gbid(isrc).src.replace(sz,ez);
	gbid('iv_img').onload = function() {
		var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
		gbid('iv_img').style.maxHeight = (y)-(10)+'px';
		gbid('iv').style.display = 'block';
		gbid('iv_wrap').style.width = gbid('iv_img').offsetWidth+'px';
		gbid('iv_wrap').style.height = (y)+'px';
		gbid('iv_wrap').style.marginLeft = -(gbid('iv_img').offsetWidth/2)-(10)+'px';
		gbid('iv_wrap').style.marginTop = -(gbid('iv_img').offsetHeight/2)-(5)+'px';
	};
}

function closeiv() {
	gbid('iv').style.display = 'none';
	gbid('iv_img').src = '';
}

function highlight(id, cls) {
	if (gbid(id).checked) {
		gbid(id+'row').className = cls;
	} else {
		gbid(id+'row').removeAttribute('class');
	}
}

function scrollto(id) {
	if((obj = document.getElementById(id)) && obj != null){
		window.scrollTo(0, obj.offsetTop - 100);
	}
}

(function( $ ){
  $.fn.eSerialize = function() {
    var returning = '';
    $('input, textarea, select',this).each(function(){
          var name = this.name;
          var value = encodeURIComponent(this.value);
		  
		  if(this.type == 'text' || this.type == 'hidden') {
			returning += name + '=' + value + '&';
		  } 
		  
		  if($(this).is('select')) {
			returning += name + '=' + $(this).val() + '&';
		  } 
		  
		  if(this.type=='checkbox') {
			if(this.checked) {
				returning += name + '=' + value + '&';
			} else {
				returning += name + '=&';
			}
		  }
		  
    })
    return returning;

  };
})( jQuery );


