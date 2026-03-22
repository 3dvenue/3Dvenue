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

if (!empty($_POST['company'])) {
    $company = $_POST['company'];
    $oname = $_POST['oname'];
    $email = $_POST['email'];
    $telno = $_POST['telno'];
    $zip = $_POST['zip'];
    $prefecture = $_POST['prefecture'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];

    $sql = "UPDATE organizer SET company=?, oname=?, email=?, telno=?, zip=?, prefecture=?, address1=?, address2=? WHERE oid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssi", $company, $oname, $email, $telno, $zip, $prefecture, $address1, $address2, $oid);
    $stmt->execute();
    header("Location: acount.php");
    exit;
}


$sql = "SELECT * FROM organizer WHERE oid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $oid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
if($row){
    $company = $row['company'];
    $oname = $row['oname'];
    $email = $row['email'];
    $password = $row['password'];
    $telno = $row['telno'];
    $zip = $row['zip'];
    $prefecture = $row['prefecture'];
    $address1 = $row['address1'];
    $address2 = $row['address2'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/acount.css?t=<?=time()?>">
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
<h2>Organizer Account</h2>
    <div id="form">
        <form method="post">
            <input type="hidden" name="id" id="id" value="<?=$oid?>" />
            <label for="company"><span>Organization Name</span><input type="text" name="company" id="company" value="<?=$company?>" placeholder="Organization name" required /></label>
            <label for="oname"><span>Contact Person</span><input type="text" name="oname" id="oname" placeholder="Contact Person" value="<?=$oname?>" required /></label>
            <label for="email"><span>email</span><input type="text" name="email" id="email" placeholder="email" value="<?=$email?>" required /></label>
            <label for="telno"><span>Phone Number</span><input type="text" name="telno" id="telno" placeholder="Phone Number" value="<?=$telno?>" required /></label>
            <label for="zip"><span>Postal Code</span><input type="text" name="zip" id="zip" placeholder="Postal Code" value="<?=$zip?>" required /></label>
            <label for="prefecture"><span>prefecture</span><input type="text" name="prefecture" id="prefecture" placeholder="prefecture" value="<?=$prefecture?>" required /></label>
            <label for="address1"><span>Address Line 1</span><input type="text" name="address1" id="address1" placeholder="Address Line 1" value="<?=$address1?>" required /></label>
            <label for="address2"><span>Address Line 2</span><input type="text" name="address2" id="address2" placeholder="Address Line 2" value="<?=$address2?>" /></label>
            <div id="button"><button type="submit" class="btn" name="submit" id="submit" value="edit">Edit</button></div>
        </form>
    </div>
</section>

</div>
</main>
<?php include_once 'footer.php'; ?>
</script>
</body>
</html>