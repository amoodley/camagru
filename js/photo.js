function videoStream() {
	var video = document.getElementById('video'),
		canvas = document.getElementById('canvas'),
		overlay = document.getElementById('myOverlay'),
		context = canvas.getContext('2d'),
		overlayContext = overlay.getContext('2d'),
		// photo = document.getElementById('photo'),

		vendorUrl = window.URL || window.webkitURL;

	navigator.getMedia = 	navigator.getUserMedia ||
							navigator.webkitGetUserMedia ||
							navigator.mozGetUserMedia ||
							navigator.msGetUserMedia;
	context.translate(640, 0);
	navigator.getMedia({
		video: true,
		audio: false
	}, function(stream) {
		video.src = vendorUrl.createObjectURL(stream);
		video.play();
	}, function(error){
		// An error occured
		// error.code
	});
}

function snapshot() {
		var video = document.getElementById('video'),
			canvas = document.getElementById('canvas'),
			overlay = document.getElementById('myOverlay'),
			context = canvas.getContext('2d'),
			overlayContext = overlay.getContext('2d');

		context.scale(-1, 1);
		document.getElementById('video').style.display = "none";
		document.getElementById('overlay').style.display = "none";
		document.getElementById('canvas').style.display = "block";
		document.getElementById('myOverlay').style.display = "block";
		document.getElementById('newPhoto').style.display = "block";
		document.getElementById('capture').style.display = "none";
		var imgsrc = document.getElementById("overlay");

		context.drawImage(video, 0, 0, 640, 480);
		overlayContext.drawImage(imgsrc, 0, 0, 640, 480);
}

function newPhoto() {
	var video = document.getElementById('video'),
		canvas = document.getElementById('canvas'),
		overlay = document.getElementById('myOverlay'),
		context = canvas.getContext('2d'),
		overlayContext = overlay.getContext('2d');

	document.getElementById('video').style.display = "block";
	document.getElementById('overlay').style.display = "block";
	document.getElementById('canvas').style.display = "none";
	document.getElementById('myOverlay').style.display = "none";
	document.getElementById('newPhoto').style.display = "none";
	document.getElementById('capture').style.display = "block";
	overlayContext.clearRect(0, 0, 640, 480);
}

function postImage() {
		var canvas = document.getElementById('canvas');
		var overlay = document.getElementById('myOverlay');
		var image = canvas.toDataURL('image/png');
		var filter = overlay.toDataURL('image/png');

		var temp = 'photo=' + encodeURIComponent(image) + '&overlay=' + encodeURIComponent(filter);
		var xhttp = new XMLHttpRequest();
		xhttp.open("POST", "save_photo.php", true);
		xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhttp.onreadystatechange = function()
		{
			if (this.readyState == 4 && this.status == 200) 
			{
    			console.log(this.responseText);
    		}
		}
		xhttp.send(temp);
		
		// photo.setAttribute('src', image);
}

function changeOverlay(input) {
	document.getElementById("overlay").src = input;
}