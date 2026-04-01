<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();

// Please set your account and password first.
// You can freely use multi-byte characters (Kanji, Hiragana, Katakana) and special symbols.
// Designed for extreme simplicity. Feel free to inject your own security logic—it only takes a few lines to turn this into a fortress.
// Note: Check the README for a collection of unconventional security ideas that can drive hackers mad.

/*
    [Security Memo]
    To drive hackers mad, you can dynamically change the 'name' attribute 
    based on the $ipcheck result (e.g., name="acount<?=$input1?>").
    This creates a "moving target" that automated bots cannot easily track. 
    Just remember to sync the receiving $_POST['acount'.$input1] logic!
    -- Concept by Yoshihiro Murai
*/

$acount = "Admin Acount";
$password = "Admin Password";

$title="Admin Login";
$ipcheck = "127.0.0.1";
$ip = $_SERVER['REMOTE_ADDR'];
$input1 = "email";
$input2 = "password";
$required = "required";
$message = "";

if($ipcheck == $ip){
    $input1 = "text";
    $input2 = "text";
    $required = "";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // // --- Sender Check (CSRF Prevention) ---
    // $referer = isset($_SERVER['HTTP_REFERER']) ? parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) : '';
    // $serverName = $_SERVER['HTTP_HOST'];

    // if ($referer !== $serverName) {
    //     header("Location: login.php"); //Jump to fake page.
    //     exit;
    // }

    if (isset($_POST['password'])) {
        $message = "Invalid email address or password.";
        // echo "Error";
    }
    if (isset($_POST['acounttext']) && isset($_POST['text'])) {
        if($_POST['acounttext'] === $acount && $_POST['text'] === $password){
        require_once "../config.php";
        // echo "Success";
            $_SESSION['ADMIN_CHECK'] = "success";
            header("Location: index.php");
            exit;
        }
    }

    if (isset($_POST['acount']) && isset($_POST['password'])) {
        if($_POST['acount'] === $acount && $_POST['password'] === $password){
        require_once "../config.php";
            $_SESSION['ADMIN_CHECK'] = "success";
            header("Location: index.php");
            exit;
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
    <link rel="icon" href="../favicon.ico">
    <title>
        <?=$title?>
    </title>
<style type="text/css">
.inner{
    max-width:680px;
}

h2{
    text-align: center;
}

.error{
    text-align: center;
    margin-bottom:40px;
}

#form{
    max-width: 420px;
    border:1px solid #999;
    background:linear-gradient(#FFF,#EEE);
    padding:20px 40px;
    border-radius: 10px;
    margin: 0 auto;
}

form label{
    display: flex;
    justify-content: space-between;
    padding:0 0 20px;
}

form label input{
    padding:5px 10px;
    border:1px solid #999;
    border-radius: 5px;
}

#submitButton{
    text-align: right;
}

#message{
    text-align: center;
    font-weight:700;
    font-size:12px;
    color:red;
}

</style>
</head>
<body>
<main>
<section id="login">
    <div class="inner">
    <h2><?=$title?></h2>

    <p class="error">Your IP address is <strong><?=$ip?></strong>.<br />
    This system does not support login from this address.</p>

    <div id="form">
        <form method="POST">
            <label><span>Email：</span><input type="<?=$input1?>" name="acount" value="<?=$input1?>" placeholder="acount@example.com" <?=$required?>></label>
            <label><span>password：</span><input type="<?=$input2?>" name="password" value="<?=$input2?>" placeholder="password" <?=$required?>></label>
            <div id="message"><?=$message?></div>
            <div id="submitButton"><button type="submit" class="btn" name="submit" value="login">ログイン</button>
        </form>
    </div>
</section>
</div>
</main>
<?php include_once '../footer.php'; ?>
</body>
</html>