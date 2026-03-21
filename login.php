<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
require_once "./config.php";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submit = $_POST['submit'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM company WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {

        if (password_verify($password, $row['password'])) {

            if ($row['status'] == 1) {

                $_SESSION['login_email'] = $email;
                $_SESSION['login_id'] = $row['cid'];
                $_SESSION['login_uuid'] = $row['uuid'];
                $_SESSION['company'] = $row['company'];
                
                header("Location: /mypage/acount.php");
                exit;
            } else {
                $error = "Your account is pending administrator approval.";
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }
}

// qxw05164Pass

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./common/css/style.css">
    <link rel="stylesheet" type="text/css" href="./common/css/login.css">
    <link rel="icon" href="./favicon.ico">
    <title>EXHIBITOR LOGIN</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<h1 id="head">EXHIBITOR LOGIN</h1>
<main>
<div class="inner">
<section id="login">
        <h2>LOGIN</h2>
        <p>
            Exhibitor registration is required prior to participation.<br />
            If you have not registered yet, please proceed from the <a href="acount.php">Exhibitor Pre-registration page</a>.
        </p>

        <div id="form">
            <form action="login.php" method="post">
                <div>
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" required placeholder="account@example.com">
                </div>
                <div id="result"><?=$error?></div>
                <div>
                    <label for="password">Password</label> 
                    <input type="password" name="password" id="password" required placeholder="password">
                </div>
                <div id="button">
                    <button type="submit" class="btn" name="submit">Login</button>
                </div>
            </form>
        </div>

    </section>

</div>
</main>
<?php include_once('footer.php')?>
