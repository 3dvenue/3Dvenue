<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();

$result = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "./config.php";
    $submit = $_POST['submit'] ?? "";
    $email = $_POST['email'] ?? "";
    $company = $_POST['company'] ?? "";
    $name = $_POST['name'] ?? "";
    $password = $_POST['password'] ?? "";
    $telno = $_POST['telno'] ?? "";
    $zip = $_POST['zip'] ?? "";
    $prefecture = $_POST['prefecture'] ?? "";
    $address1 = $_POST['address1'] ?? "";
    $address2 = $_POST['address2'] ?? "";

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
            $result = "Password must be 8+ chars with uppercase, lowercase, and numbers.";
        } elseif (strpos(strtolower($password), 'password') !== false) {
            $result = "This password is too simple.";
        } else {

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT IGNORE INTO company (email, company, name, password, telno, zip, prefecture, address1, address2) VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss",$email, $company, $name, $hashed_password, $telno, $zip, $prefecture, $address1, $address2);
    $stmt->execute();
        if ($stmt->affected_rows > 0) {
                $result = "Registration Successful";
        } else {
                $result = "Email Already Registered";
        }

    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./common/css/base.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="./common/css/style.css?t=<?=time()?>">
    <link rel="stylesheet" type="text/css" href="./common/css/login.css?t=<?=time()?>">
    <link rel=”icon” href=“../favicon.ico”>
    <title>Exhibitor Pre registration</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<h1 id="head">Exhibitor Pre registration</h1>
<main>
<div class="inner">
    <section id="login">
        <h2>Pre Application From</h2>
        <ol>
            <li>To ensure 3DVenue operates reliably across all server environments, we do not use automatic email notifications.</li>
            <li>Submission: Enter your Email/Password and submit your information.</li>
            <li>Review: The administrator will review your application for safety.</li>
            <li>Activation: Your account will be activated upon approval. You cannot log in until the approval process is complete.</li>
            <li>Note: To protect the community, the administrator reserves the right to decline or remove entries at their discretion.</li>
        </ol>

        <div id="form">
            <form method="post">
                <div>
                    <div id="result"><?=$result?></div>
                    <label for="company">company</label>
                    <input type="text" name="company" id="company" value="" placeholder="comapnyname" required />
                    <label for="name">name</label>
                    <input type="text" name="name" id="name" value="" placeholder="name" required />
                    <label for="email">email</label>
                    <input type="text" name="email" id="email" value="" placeholder="acount@example.com" required />
                    <label for="password">password</label>
                    <input type="password" name="password" id="password" value="" placeholder="8+ chars (incl. A, a, 1)" required />
                    <label for="telno">telno</label>
                    <input type="telno" name="telno" id="telno" value="" placeholder="Phone Number" required />
                    <label for="zip">zip</label>
                    <input type="text" name="zip" id="zip" value="" placeholder="Postal Code" required />
                    <label for="prefecture">prefecture</label>
                    <input type="text" name="prefecture" id="prefecture" value="" placeholder="" required />
                    <label for="address1">address1</label>
                    <input type="text" name="address1" id="address1" value="" placeholder="" required />
                    <label for="address2">address2</label>
                    <input type="text" name="address2" id="address2" value="" placeholder="" />
                    <div id="applicationbtn"><button type="submit" name="submit" class="btn" value="register">APPLICATION</button></div> 
                </div>
            </form>
        </div>
    </section>
</div>
</main>
<?php include_once('footer.php')?>
</body>
</html>