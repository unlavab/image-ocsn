<!DOCTYPE html>
<html>
<head>
	<title>Image-ocsn</title>
	<link  href="node_modules/cropperjs/dist/cropper.min.css" rel="stylesheet">
	<style type="text/css">
		img {
		  	max-width: 100%; /* This rule is very important, please do not ignore this! */
		}
	</style>
</head>
<body>

<h1>Hello world</h1>

<div>
  <img id="image" src="input/main.jpg">
</div>

<script src="node_modules/cropperjs/dist/cropper.min.js"></script>
<script type="text/javascript">
	const image = document.getElementById('image');
	const cropper = new Cropper(image, {
	  	aspectRatio: 16 / 9,
	  	rotatable: true,
	  	crop(event) {
	  		
	  	},
	});
</script>
</body>
</html>