<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
$oid = $_SESSION['oid'];
require_once "../config.php";

$name = "New Expo Title";
$subtitle = "Subtitle or English Title goes here";
$description = "Please enter the expo description within 500 characters.";
$start = date('Y-m-d', strtotime('+30 days')); // 1か月後
$end = date('Y-m-d', strtotime('+37 days')); // 1か月後

$sql = "INSERT INTO venue (name,subtitle,description,start,end,organizer) VALUES (?,?,?,?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('sssssi',$name ,$subtitle ,$description ,$start ,$end ,$oid);

if ($stmt->execute()) {

    $newId = $conn->insert_id;

    $dirPath = '../que/' . $newId;
    if (!is_dir($dirPath)) {
        mkdir($dirPath, 0777, true);
        chmod($dirPath, 0777);
    }

    $logFile = $dirPath . '/access.log';
    if (!file_exists($logFile)) {
        touch($logFile);
        chmod($logFile, 0666);
    }
}

$stmt->close();
header("Location: expo.php");
exit;
?>