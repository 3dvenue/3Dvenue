<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

require_once "../config.php";
include_once "auth.php";

$status = "";
$logo = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       $submit = $_POST['submit'] ?? null;

       if($submit === "update"){
           $company = $_POST['company'] ?? null;
           $name = $_POST['name'] ?? null;
           $telno = $_POST['telno'] ?? null;
           $zip = $_POST['zip'] ?? null;
           $prefecture = $_POST['prefecture'] ?? null;
           $address1 = $_POST['address1'] ?? null;
           $address2 = $_POST['address2'] ?? null;

            $sql = "UPDATE company SET company = ?, name = ?, telno = ?, zip = ?, prefecture = ?, address1 = ?, address2 = ? WHERE cid = ? AND uuid = ? AND email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssiss", $company, $name, $telno, $zip, $prefecture, $address1, $address2, $login_id, $login_uuid, $login_email);
            $stmt->execute();

            $_SESSION['company'] = $company;
        }

        if (isset($_POST['submit']) && $_POST['submit'] === 'logupload') {
            if (isset($_FILES['logomark']) && $_FILES['logomark']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = '../logo/';
                $tmpName = $_FILES['logomark']['tmp_name'];
                
                $image = imagecreatefromstring(file_get_contents($tmpName));
                
                if ($image) {
                    imagealphablending($image, false);
                    imagesavealpha($image, true);

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $newFileName = $login_id . '.webp';
                    $targetPath = $uploadDir . $newFileName;

                    if (imagewebp($image, $targetPath, 90)) {
                        imagedestroy($image);
                        $ext = "webp";
                        $sql = "UPDATE company SET logo = ? WHERE cid = ? AND uuid = ? AND email = ?";   
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("siss", $ext, $login_id, $login_uuid, $login_email);
                        $stmt->execute();                        
                        header("Location: acount.php");
                        exit;
                    } else {
                        $status = "WebP変換に失敗しました。";
                    }
                    imagedestroy($image);
                } else {
                    $status = "Upload failed.";
                }
            } else {
                    $status = "An error occurred during upload.";
            }
        }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/acount.css">
    <link rel="icon" href="../favicon.ico">
    <title>My Account</title>
<style type="text/css">
table{

}
</style>
</head>
<body class="acount">
<?php include_once 'header.php'; ?>
<main>
<div class="inner">

<section id="acount">
<h2>My Account</h2>
<p class="infomation">
To use our service, registration of the following information is required.<br />
Please enter the basic information required for exhibitors, such as company name, contact person's name, contact details, and location.<br /><br />

Registered details will be used for procedures and support.<br />
We appreciate your cooperation in providing accurate information.<br /><br />

*If some items are left blank, you may be unable to use the service.
</p>
<?php
$logoimage = "";
$classname = "";
$sql = "SELECT * FROM company WHERE cid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $login_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $company = $row['company'];
    $name = $row['name'];
    $telno = $row['telno'];
    $zip = $row['zip'];
    $prefecture = $row['prefecture'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
    $logo = $row['logo'];
    if(!empty($logo)){
      $timestamp = time();
      $logoimage = "background-image:url(../logo/{$login_id}.webp?t={$timestamp})";
      $classname = "active";
    }
?>
<?php } ?>

<h3>Logo Mark</h3>

<div id="status"><?=$status?></div>

<div id="logo" style="<?=$logoimage?>" class="<?=$classname?>">
    <form method="post" enctype="multipart/form-data">
        <label for="logomark">Select Logo</label><br>
        <input type="file" name="logomark" id="logomark" accept=".jpg,.jpeg,.png"><br><br>
        <button type="submit" name="submit" value="logupload" class="btn">Upload</button>
    </form>
</div>

<h3>Account Settings</h3>

<div id="acountform">
    <form method="post">
        <div>
            <label for="company">Company Name (*)</label>
            <input type="text" id="company" name="company" autocomplete="organization" maxlength="100" value="<?=$company?>" required>
        </div>

        <div>
            <label for="name">Contact Person (*)</label>
            <input type="text" id="name" name="name" maxlength="100" autocomplete="name" value="<?=$name?>" required>
        </div>

        <div>
            <label for="telno">Phone Number (*)</label>
            <input type="tel" id="telno" name="telno" maxlength="15" autocomplete="tel" value="<?=$telno?>" required>
        </div>

        <div>
            <label for="zip">Postal Code (*)</label>
            <input type="text" id="zip" name="zip" maxlength="8" autocomplete="postal-code" value="<?=$zip?>" required>
        </div>

        <div>
            <label for="prefecture">State / Prefecture (*)</label>
            <input type="text" id="prefecture" name="prefecture" maxlength="41" value="<?=$prefecture?>" required>
        </div>

        <div>
            <label for="address1">Address Line 1 (*)</label>
            <input type="text" id="address1" name="address1" autocomplete="address-line1" value="<?=$address1?>" required>
        </div>

        <div>
            <label for="address2">Address Line 2 (*)</label>
            <input type="text" id="address2" name="address2" autocomplete="address-line2" value="<?=$address2?>">
        </div>

        <div style="justify-content: right;">(*) Required</div>

        <div>
            <button type="submit" class="btn" name="submit" value="update"><span class="add">Save</span></button>
        </div>
    </form>
</div>
</section>

</div>
</main>
<?php include_once 'footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
$(function(){


    $('#logomark').on('change',function(e){
        $('#logo').addClass('change');
        const file = e.target.files[0];
        if (!file) return;

        const validTypes = ['image/jpeg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            $('#status').text('JPEGまたはPNG画像を選んでください。');
            $(this).val(''); // Select Reset
            $('#logo').removeClass();
            return;
        }

        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(event) {
                $('#logo').css('background-image', `url(${event.target.result})`);
            };
            reader.readAsDataURL(file);
        }

    })

})
</script>
</body>
</html>