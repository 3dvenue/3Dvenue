<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

require_once('auth.php');
require_once "../config.php";

$vid = $_SESSION['vid'] ?? 0;
$cid = $login_id;

$sql = "SELECT * FROM venue WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	$submit = $_POST['submit'] ?? null;

	if($submit == "venue"){
		$vid = $_POST['vid'];
	 	$_SESSION['vid'] = $vid;
	}

	if($submit == "edit"){
		$vid = $_POST['vid'];
	 	$_SESSION['vid'] = $vid;
	    header("Location: exhibit.php");
	    exit;
	}

	$title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
	$subtitle = mysqli_real_escape_string($conn, $_POST['subtitle'] ?? '');
	$description = mysqli_real_escape_string($conn, $_POST['description'] ?? '');
	$category = mysqli_real_escape_string($conn, $_POST['category'] ?? 0);
	$url = mysqli_real_escape_string($conn, $_POST['url'] ?? '');
	$telno = mysqli_real_escape_string($conn, $_POST['telno'] ?? '');

	if($submit == "add"){

		$sql = "INSERT INTO exhibitors (cid,vid,title,subtitle,description,category,url,telno,tax) VALUES ('$cid','$vid','$title','$subtitle','$description','$category','$url','$telno','$tax')";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute();
	    header("Location: exhibit.php");
	    exit;
	}

	if($submit == "update"){
		$sql = "UPDATE exhibitors SET title = '$title',subtitle = '$subtitle',description = '$description',category = '$category',url = '$url',telno = '$telno' WHERE cid = '$cid' AND vid = '$vid'";
	    $stmt = $conn->prepare($sql);
	    $stmt->execute();
	    header("Location: exhibit.php");
	    exit;
	}


$logoimage = "../logo/".$cid.'.webp?t='.time();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/exhibit.css">
    <link rel="icon" href="../favicon.ico">
    <title></title>
<style type="text/css"></style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
<?php
$mycard = "../que/".$vid."/".$cid.".webp";
$mypath = "";
$path = $mycard;
if (file_exists($path)) {
	$mypath = "card";
}

$title ?? null;
$subtitle ?? null;
$description ?? null;
$category ?? null;
$url ?? null;
$telno ?? null;
$image ?? null;
$status = "";

$sql = "SELECT * FROM exhibitors WHERE cid = '$cid' AND vid = '$vid'";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $title = $row['title'];
    $subtitle = $row['subtitle'];
    $description = $row['description'];
    $category = $row['category'];
    $url = $row['url'];
    $telno = $row['telno'];
    $image = $row['image'];
	$status = "update";
} 


?>

<ol id="status" class="<?=$mypath?> <?=$status?>">
	<li class="step1">Create Card</li>
	<li class="step2">Add Profile</li>
	<li class="step3">Finish</li>
</ol>

<div class="inner">

<section id="acount">
<h2>Exhibitor Registration</h2>
<p class="infomation">
</p>

<?php
$logoimage = "";
$sql = "SELECT * FROM company WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i',$cid);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $company = $row['company'];
    $name = $row['name'];
    if($telno === ""){
	    $telno = $row['telno'];
    }
    $zip = $row['zip'];
    $prefecture = $row['prefecture'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
    $logo = $row['logo'];
    if(!empty($logo)){
    	$logoimage = "../logo/{$login_id}.{$logo}";
    }
 } 
?>


<section id="venue-card" class="<?=$mypath?>">
	<h3>Exhibition Card</h3>
<?php 
	if($mypath == "card"){
?> 
<img src="<?=$mycard?>?t=<?=time()?>" alt="出展カード" />
<?php } ?>
</section>

<section id="makecard" class="<?=$mypath?>">

<h4>Create Exhibition Card</h4>

<p id="cardmemo">
<strong>This card identifies your booth during the exhibition.</strong>
<span>Click the area below to create or upload your card.</span>
<span>Supported formats: JPG, PNG</span>
</p>

<div id="card">
	<div id="mycard" style="background:linear-gradient(#fff,#ccc);">
		<div id="logoimage" style="width:300px;top:40px;left:160px;background-image:url(<?=$logoimage?>?t=<?=time()?>);"><span id="caption">LOGO</span></div>
		<div id="companyname" style="font-size:30px;text-align:center;top:380px;left:10px;"><?=$company?></div>
	</div>
	<div id="editor">
		<div id="cardclose">&times;</div>

		<div id="text">
			Company Name:
			<span id="textshow"></span>
			<span id="textleft" class="align left"><img src="../img/align-left.svg"></span>
			<span id="textcenter"class="align center"><img src="../img/align-center.svg"></span>
			<span id="textright" class="align right"><img src="../img/align-right.svg"></span>
			<span id="bold"><img src="../img/bold.svg"></span>
			<span><input type="color" id="fontcolor" name="#000000"></span>
			<span><input type="number" id="fontsize" max="50" min="14" step="0.1" value="30">px</span>
			<span id="textup" class="updown"><img src="../img/up.svg"></span>
			<span id="textdown" class="updown"><img src="../img/down.svg"></span>
		</div>

		<div id="background">
			Background:
			<input type="color" id="bgcolor1" name="bgcolor1" value="#FFFFFF">
			<input type="color" id="bgcolor2" name="bgcolor2" value="#CCCCCC">
			<span id="backgroundupload" class="upload"><label class="uploadbtn" for="imageupload"><img src="../img/cloud.svg"></label></span>
			<span id="trash"><img src="../img/trash.svg"></span>
		</div>

		<div id="logo">
			Logo:
			<span id="logoshow"></span>
			<input type="checkbox" id="show" value="1">
			<span id="big" class="size"><img src="../img/small.svg"></span>
			<span id="small" class="size"><img src="../img/big.svg"></span>
			<span id="logoleft" class="left align"><img src="../img/left.svg"></span>
			<span id="logoright" class="right align"><img src="../img/right.svg"></span>
			<span id="logoup" class="up updown"><img src="../img/up.svg"></span>
			<span id="logodown" class="down updown"><img src="../img/down.svg"></span>
			<span id="logoupload" class="upload"><label class="uploadbtn" for="imageupload"><img src="../img/cloud.svg"></label></span>
		</div>
		<div id="editbtn"><span id="editbtn"><button id="cardset" class="btn">Card Preview</button></span></div>

	</div>
</div><!-- card -->
</section>


<section id="infomation" class="<?=$mypath?>">
	<form method="post" name="image" id="image">
		<input type="file" name="imageupload" id="imageupload" class="" />
	</form>

	<div id="textform">

		<h3>Edit Exhibition Profile</h3>
		<p class="note <?=$status?>">
			Create a compelling profile to showcase your brand's unique value.
		</p>

		<form method="post" name="text" id="text">

			<table class="<?=$status?>">

				<tr>
					<th><label for="title">Catchphrase</label></th>
					<td><input type="text" name="title" id="title" value="<?=$title?>" placeholder="Enter your catchphrase" required></td>
				</tr>

				<tr>
					<th><label for="subtitle">Subtitle</label></th>
					<td><input type="text" name="subtitle" id="subtitle" value="<?=$subtitle?>" placeholder="Enter a subtitle" required></td>
				</tr>

				<tr>
					<th><label for="description">Exhibition Overview</label></th>
					<td><textarea name="description" id="description" placeholder="Describe your exhibition details..." required><?=$description?></textarea></td>
				</tr>

				<tr>
					<th><label for="category">Exhibition Category</label></th>
					<td>
						<select name="category" id="category">
						<option value="">Select a category</option>
						<?php
							$sql = "SELECT * FROM category WHERE vid = ?";
							$stmt = $conn->prepare($sql);
							$stmt->bind_param("i", $vid);
							$stmt->execute();
							$result = $stmt->get_result();
							while($row = $result->fetch_assoc()) {
								$selected = "";
								$c_id = $row['category_id'];
								$name = $row['name'];
								if($category == $c_id){
									$selected = "selected";
								}
						?>
						<option value="<?=$c_id?>" <?=$selected?>><?=$name?></option>
						<?php } ?>
						</select>
					</td>
				</tr>

				<tr>
					<th><label for="link">Showcase Website</label></th>
					<td><input type="url" name="url" id="url" value="<?=$url?>" placeholder="https://your-showcase.com/targeturl/" required></td>
				</tr>

				<tr>
					<th><label for="telno">Contact Phone Number</label></th>
					<td><input type="tel" name="telno" id="telno" pattern="0\d{1,4}-\d{1,4}-\d{4}" value="<?=$telno?>" placeholder="Enter contact number" required></td>
				</tr>

				<tr>
					<td colspan="2" class="buttonbox">
						<button type="submit" name="submit" id="add" class="btn" value="add">Register</button>
						<button type="submit" name="submit" id="update" class="btn" value="update">Update</button>
					</td>
				</tr>
			</table>

		</form>

	</div>

</section>

</div>
</main>

<div id="view">
	<div id="closeview">&times;</div>
	<h3>Card Preview</h3>
	<div id="result"></div>
	<p>
	This image will be saved and displayed at your booth.<br />
	Right-click to download a copy for your records.<br />
	This file can also be reused for future exhibitions.<br />
	<span>* Uploaded as background image.</span>
	</p>
	<button id="dataupload" class="btn">Save & Continue</button>
</div>

<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script src="../common/js/dom-to-image.min.js"></script>
<script type="text/javascript">
$(function(){

    let prefecture = "<?=$prefecture?>";
    $('#prefecture').val('<?=$prefecture?>');

    let timer;
    const wait = 50;
    $('#editor span').on('mousedown',function(){
    	clearInterval(timer);
    	let top = 0;
    	let left = 0;
    	let id = $(this).attr('id');
    	// console.log(id);

		switch (id) {
		  case "trash": //Background Dlete
		  	$('#mycard').css({'background-image':'none'});
		    break;

		  case "textleft": //Left
		  	$('#companyname').css({'text-align':'left'});
		    break;

		  case "textcenter": //Center
		  	$('#companyname').css({'text-align':'center'});
		    break;

		  case "textright": //Right
		  	$('#companyname').css({'text-align':'right'});
		    break;

		  case "textup": //Up
			  	top = parseInt($('#companyname').css('top'));
		  	timer = setInterval(function(){
			  	top--;
			  	$('#companyname').css({'top':top+'px'});
		  	},wait);
		    break;

		  case "textdown": //Down
			  	top = parseInt($('#companyname').css('top'));
		  	timer = setInterval(function(){
			  	top++;
			  	$('#companyname').css({'top':top+'px'})
		  	},wait);
		    break;

		  case "logoshow": 
		  	$('#show')
		    break;

		  case "big": //Zoom In
			  	size = parseInt($('#logoimage').css('width'));
		  	timer = setInterval(function(){
			  	size++;
			  	$('#logoimage').css({'width':size+'px'})
		  	},wait);
		    break;

		  case "small": //Zoom Out
			  	size = parseInt($('#logoimage').css('width'));
		  	timer = setInterval(function(){
			  	size--;
			  	$('#logoimage').css({'width':size+'px'})
		  	},wait);
		    break;

		  case "logoleft": //Left
			  	left = parseInt($('#logoimage').css('left'));
		  	timer = setInterval(function(){
			  	left--;
			  	$('#logoimage').css({'left':left+'px'})
		  	},wait);
		    break;

		  case "logoright": //Right
			  	left = parseInt($('#logoimage').css('left'));
		  	timer = setInterval(function(){
			  	left++;
			  	$('#logoimage').css({'left':left+'px'})
		  	},wait);
		    break;

		  case "logoup": //Up
			  	top = parseInt($('#logoimage').css('top'));
		  	timer = setInterval(function(){
			  	top--;
			  	$('#logoimage').css({'top':top+'px'})
		  	},wait);
		    break;

		  case "logodown": //Down
			  	top = parseInt($('#logoimage').css('top'));
		  	timer = setInterval(function(){
			  	top++;
			  	$('#logoimage').css({'top':top+'px'})
		  	},wait);
		    break;
		}

    })

    $(document).on('mouseup',function(){
		clearInterval(timer);    	
    })

    $('#mycard').on('click',function(){
    	$('#card').removeClass();
    	$('#card').addClass('background');
    	$('#imageupload').removeClass();
    	$('#imageupload').addClass('background');
    });

    $('#companyname').on('click',function(e){
    	let size = parseInt($(this).css('font-size'));
    	$('#fontsize').val(size);
    	e.stopPropagation();
    	$('#card').removeClass();
    	$('#card').addClass('text');
    });

    $('#logoimage').on('click',function(e){
		e.stopPropagation();
    	$('#card').removeClass();
    	$('#card').addClass('logo');
    	$('#imageupload').removeClass();
    	$('#imageupload').addClass('logo');
    });

    $('#fontsize').on('input',function(){
    	let size = $('#fontsize').val();
    	$('#companyname').css({'font-size':size+'px'});
    })

    $('#editor span').on('click',function(){
    	let id = $(this).attr('id');
		switch (id) {
		  case "bold": //Bold Type
		  	$('#companyname').toggleClass('bold');
		    break;

		  case "textshow": //Show 
		  	$('#companyname').toggleClass('hidden');
		    break;

		  case "logoshow": //Hidden
		  	$('#logoimage').toggleClass('hidden');
		    break;
		}

    });

    $('#editor input').on('input',function(){
    	let id = $(this).attr('id');
    	// console.log(id);
		switch (id) {
	
		  case "fontcolor": //Text Color
	    	let color = $(this).val();
		  	$('#companyname').css({'color':color});
		    break;

		  case "bgcolor1": //Top Color
		  case "bgcolor2": //Bottom Color
	    	let color1 = $('#bgcolor1').val();
	    	let color2 = $('#bgcolor2').val();
		  	$('#mycard').css({'background':'linear-gradient('+color1+','+color2+')'});
		    break;

		}

    })


	$('#imageupload').on('change', function () {
	  let name = $('#imageupload').attr('class');
		  console.log(name);

		  const file = this.files[0];
		  if (!file) return;
		  const reader = new FileReader();
		  reader.onload = function (e) {
		  	if(name == 'logo'){
			    $('#logoimage').css('background-image', `url(${e.target.result})`);
		  	}

		  	if(name == 'background'){
			    $('#mycard').css('background-image', `url(${e.target.result})`);
		  	}

		  };
		  reader.readAsDataURL(file);
	});

    $('#cardclose').on('click',function(){
    	$('#card').removeClass();
    })

    $('#cardset').on('click',function(){
    	MakeBana();
    })

    $('#closeview').on('click',function(){
    	$('#view').removeClass('view');
    })

 	// Create Bana
    function MakeBana(){
    	$('#card').removeClass();
    	$('#view').addClass('view');
        const node = $('#mycard').get(0);
        domtoimage.toPng(node)
        .then(function (dataUrl) {
            $('#result').empty().append('<img src="' + dataUrl + '">');
            // $('#eyecatch').removeClass('view');
        })
        .catch(function (error) {
            alert('Banner creation failed');
        });
    };

	$('#dataupload').on('click', function () {
	  const base64 = $('#result img').attr('src'); // base64データ
	  $.post('uploadlogo.php', {
	    image: base64
	  }).done(function (res) {
	    alert('Sccess');
	    location.reload(true);
	  }).fail(function (xhr) {
	    alert('Erorr: ' + xhr.responseText);
	  });
	    $('#view').removeClass();
	});


	// Check card view
	function cardReset(){
		$('#mycard').css({'background-image': 'url("../que/<?= $vid ?>/<?= $cid ?>.webp")'});
		$('#logoimage,#companyname').addClass('hidden');
	}

	$('#venue-card img').on('click',function(){
		$('#venue-card,#makecard').removeClass('card');
	})

})
</script>
</body>
</html>
