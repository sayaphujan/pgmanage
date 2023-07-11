$(function() {
	var videoElement = document.querySelector('video#camera_video');
	var videoSelect = document.querySelector('select#videoSource');
	
	var videoSource;
	
	var launched = false;

	navigator.getUserMedia = navigator.getUserMedia ||
	  navigator.webkitGetUserMedia || navigator.mozGetUserMedia;

	function gotSources(sourceInfos) {
	  for (var i = 0; i !== sourceInfos.length; ++i) {
		var sourceInfo = sourceInfos[i];
		var option = document.createElement('option');
		option.value = sourceInfo.id;
		if (sourceInfo.kind === 'video') {
		
		  option.text = sourceInfo.label || 'camera ' + (videoSelect.length + 1);
		  videoSelect.appendChild(option);
		  
		  if(sourceInfo.label.indexOf('back') >=0 ) {
			//alert(sourceInfo.id+ ' ' +sourceInfo.label);
			//videoSource = sourceInfo.id;
		  }
		
		}
		
	  }
  
  
	}

	if (typeof MediaStreamTrack === 'undefined' ||
		typeof MediaStreamTrack.getSources === 'undefined') {
	  console.log('This browser does not support MediaStreamTrack.\n\nTry Chrome.');
	} else {
	  MediaStreamTrack.getSources(gotSources);
	}

	function successCallback(stream) {
	  window.stream = stream; // make stream available to console
	  videoElement.src = window.URL.createObjectURL(stream);
	  videoElement.play();
	}

	function errorCallback(error) {
	  console.log('navigator.getUserMedia error: ', error);
	}

	function start_video() {
		
	  if (launched == false) {
		videoSource = $('#videoSource').val();
		$('#videoSource').hide();
	  }
	  
	  if(videoSource!=='') {
		  var constraints = {
			video: {
			  optional: [{
				sourceId: videoSource
			  }]
			}
		  };
	
		  navigator.getUserMedia(constraints, successCallback, errorCallback);
		  launched = true;
	  }
	  
	  
	}

	videoSelect.onchange = start_video;
	
	function open_camera() {
		$('#take-picture').modal('show');
		if (launched == true) {
			 start_video();
		}	
		//$( '#test_camera' ).photobooth();
	}
	
	$('#take_img_btn').click(function() {
	
		 open_camera();
		
		
	});

	$('#camera_video').click(function() {
		
		var video = document.querySelector('video#camera_video');
		var canvas = document.querySelector('canvas#picture_canvas');
		
		canvas.width=video.videoWidth;
		canvas.height=video.videoHeight;
		var ctx = canvas.getContext('2d');
		
		
		
		ctx.drawImage(video, 0, 0);
		$.post(image_post_url, 'base64='+encodeURIComponent(canvas.toDataURL('image/png')), null, 'script');
		
		var track = stream.getTracks()[0];
		track.stop();

	});

	
	
});