<?php
// Author: Erkhembilts Ts
// 2018-07-17

$author = "Erkhembileg.Ts";
$name = "Mongolian id cards";
$description = "Mongolian id card dataset preperation for deep learning";
$file = "mon_id.json";
$dir = glob('input/*.{jpg,png}', GLOB_BRACE);

if (!file_exists("mon_id.json")) {
	// create
	$result = array();
	$result['name'] = $name;
	$result['description'] = $description;
	$result['author'] = $author;

	foreach($dir as $key => $file) {
		$result['data'][$key] = array(
			"key" => $key,
			"filename" => $file,
			"labeled" => false,
		);
	};

	$fp = fopen("mon_id.json", 'w');
	fwrite($fp, json_encode($result, JSON_PRETTY_PRINT));   //here it will print the array pretty
	fclose($fp);
}

$source = file_get_contents("mon_id.json");

$input = json_decode($source, true);

$working_data = null;

foreach ($input['data'] as $key => $each) {
	
	if (!$each['labeled']) {
		$working_data = $each;
		break;
	}

}

?>

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
<?php if (isset($working_data)) { ?>

<div style="width: 800px; text-align: center;">

	<h1><?php echo $input['name']; ?></h1>
	<p><?php echo $input['description']; ?></p>
	<h3><?php echo $input['author']; ?></h3>

	<div style="margin-bottom: 20px;">
		<button onclick="onLeft()">Left</button>
		<button onclick="onRight()">Right</button>
	</div>

	<div style="width: 800px; height: 500px;">
	  <img id="image" src="<?php echo $working_data['filename']; ?>">
	</div>

</div>

<div style="width: 400px; margin: 100px 0 0 840px; position: absolute; top: 0;">
	<img id="preview">
	<button onclick="onSave()" style="margin-top: 20px; width: 100px; height: 100px;">SAVE!</button>
	<button onclick="onSkip()" style="margin-top: 20px; width: 50px; height: 50px;">skip</button>
</div>

<?php } else { ?>

<div style="width: 800px; text-align: center;">

	<h1><?php echo $input['name']; ?></h1>
	<p><?php echo $input['description']; ?></p>
	<h3><?php echo $input['author']; ?></h3>

	<h1>NO IMAGE FOUND!</h1>
</div>

<?php } ?>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="node_modules/cropperjs/dist/cropper.min.js"></script>
<script type="text/javascript">
	const image = document.getElementById('image');
	const preview = document.getElementById('preview');
	const cropper = new Cropper(image, {
	  	rotatable: true,
	  	viewMode: 0,
        guides: true,
        center: true,
        highlight: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        cropend:  function () {
        	canvas = cropper.getCroppedCanvas();
          	preview.src = canvas.toDataURL(); 
        },
	});

	function onLeft() {
		cropper.rotate(-90);
	}

	function onRight() {
		cropper.rotate(90);
	}

	function onSave() {
		cropper.getCroppedCanvas();

		cropper.getCroppedCanvas({
		  	fillColor: '#fff',
		  	imageSmoothingEnabled: false,
		  	imageSmoothingQuality: 'high',
		});

		cropper.getCroppedCanvas().toBlob((blob) => {
		  	const formData = new FormData();
		  	formData.append('croppedImage', blob);
		  	formData.append('skip', false);
		  	formData.append('key', "<?php echo $working_data['key']; ?>");
		  
		  	// Use `jQuery.ajax` method
		  	$.ajax('proccess.php', {
			    method: "POST",
			    data: formData,
			    processData: false,
	    		contentType: false,
			    success() {
			    	location.reload();
			    },
			    error() {
			      console.log('Upload error');
			    },
		  	});
		});
	}

	function onSkip() {
		const formData = new FormData();
		formData.append('croppedImage', false);
		formData.append('skip', true);
		formData.append('key', "<?php echo $working_data['key']; ?>");

		$.ajax('proccess.php', {
			method: "POST",
			data: formData,
			processData: false,
	    	contentType: false,
			success() {
			    location.reload();
			},
			error() {
			    console.log('Upload error');
			},
		});
	}
</script>
</body>
</html>