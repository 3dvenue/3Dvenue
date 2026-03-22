<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
$vid = $_SESSION['vid'] ?? 0;
$cid = $login_id;

if (isset($_POST['image'])){
    $imgData = $_POST['image'];
    $imgData = preg_replace('/^data:image\/png;base64,/', '', $imgData);
    $imgData = base64_decode($imgData);

    $tmpFile = tempnam(sys_get_temp_dir(), 'domimg_');
    file_put_contents($tmpFile, $imgData);

    $image = imagecreatefrompng($tmpFile);
    if (!$image) {
        http_response_code(500);
        exit("Failed to Load Images.");
    }

    $dir = "../expo/$vid";
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filePath = "$dir/$cid.jpg";
    if (imagejpeg($image, $filePath, 85)) {
        echo "Uploaded successfully: $cid.jpg";
    } else {
        http_response_code(500);
        echo "Upload failed.";
    }

    imagedestroy($image);
    unlink($tmpFile);
} else {
    http_response_code(400);
    echo "Required data is missing.";
}

?>
