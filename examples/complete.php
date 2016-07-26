<?php
/**
*	include FFmpeg class
**/
include DIRNAME(DIRNAME(__FILE__)).'/src/FFmpeg.php';

/**
*	get options from database
**/
$options = array(
	'duration'	=>	99,
	'position'	=>	0,
	'itsoffset'	=>	2,
);
$key = 'acodec';
$value = 'AAC';
$textOverlay = isset($_POST['overlay_text']) ? $_POST['overlay_text'] : 'Sample';
$fontStle = isset($_POST['font_style']) && !empty($_POST['font_style']) ? $_POST['font_style'] : '1';
$fontSize = isset($_POST['font_size']) && !empty($_POST['font_size']) ? $_POST['font_size'] : 11;
$resetAll = isset($_POST['reset']) ? true : false;
$textValue = $textOverlay;
switch ($fontStle) {
	case 1:
		$font_style = '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Italic.ttf';
		break;
	case 2:
		$font_style = '/usr/share/fonts/opentype/noto/NotoSansCJK-Bold.ttc';
		break;
	
	default:
		$font_style = '/usr/share/fonts/truetype/dejavu/DejaVuSerif-Italic.ttf';
		break;
}
$overlayText = '-vf drawtext="fontfile='. $font_style .':\text='. $textValue .': fontcolor=white: fontsize='.$fontSize.':  box=1: boxcolor=black@0.5: \ boxborderw=5: x=(w-text_w)/2: y=(h-text_h)/2" -codec:a copy';
/**
*	Create command
*/
$FFmpeg = new FFmpeg( '/usr/bin/ffmpeg' );
$FFmpeg->input( '/var/www/html/centaur.mpg' );

if (!$resetAll) {
	$FFmpeg->sameq('-i /var/www/html/logo.png \-filter_complex "overlay=x=(main_w-overlay_w)/12:y=(main_h-overlay_h)/12"');
}

// $FFmpeg->transpose( 0 )->vflip()->grayScale()->vcodec('h264')->frameRate('30000/1001');
// $FFmpeg->acodec( 'aac' )->audioBitrate( '192k' );
// foreach( $options AS $option => $values )
// {

// 	$FFmpeg->call( $option , $values );
// }

$FFmpeg->output('/var/www/html/ffmpegdemo/examples/outputs/new.mp4' , 'mp4' )->ready();

if (isset($_POST['overlay_text'])) {
	$FFmpeg = new FFmpeg( '/usr/bin/ffmpeg' );
	$FFmpeg->input( '/var/www/html/ffmpegdemo/examples/outputs/new.mp4' );

	$FFmpeg->sameq($overlayText);

	$FFmpeg->output('/var/www/html/ffmpegdemo/examples/outputs/new.mp4' , 'mp4' )->ready();
}

// print($FFmpeg->command);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Demo</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<div class="row">
		<form action="" method="post">
			<div class="col-md-8">
				<div class="col-md-4">
					<div class="form-group">
						<input type="text" class="form-control" name="overlay_text" value="" placeholder="Enter Text">
					</div>
					
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<select name="font_style" class="form-control">
						  <option value="">Select Any font style</option>
						  <option value="1">Italic</option>
						  <option value="2">Bold</option>
						  <!-- <option value="mercedes">Mercedes</option>
						  <option value="audi">Audi</option> -->
						</select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<select name="font_size" class="form-control">
						  <option value="">Select Font Size</option>
						  <?php for ($i=1; $i<100; $i++) { ?>
						  <option value="<?php echo $i;?>"><?php echo $i; ?></option>
						  <?php } ?>
						  <!-- <option value="mercedes">Mercedes</option>
						  <option value="audi">Audi</option> -->
						</select>
					</div>
				</div>
			</div>
			
			<div class="col-md-4">
				<div class="form-group">
					<input type="file" class="form-control" name="image">
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<button type="submit" class="btn  btn-primary">Save</button>
					<input type="submit" class="btn btn-danger" name="reset" value="Reset All">
				</div>
			</div>
		</form>
		</div>
		<div class="row">
			<div class="col-md-6">
				<h2>Orignal video</h2>
				<div class="">
					<video width="320" height="240" controls>
					  <source src="./inputs/centaur.mp4" type="video/mp4">
					</video>
				</div>
			</div>
			<div class="col-md-6">
			<h2>Edited Video</h2>
			<video width="320" height="240" controls>
			  <source src="./outputs/new.mp4" type="video/mp4">
			</video>
			<p>Logo has added by default</p>
			<p>
				<a href="./outputs/new.mp4" download>Download Video</a>
			</p>
			</div>
		</div>
	</div>
</div>
</body>
</html>


