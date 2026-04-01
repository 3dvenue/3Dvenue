<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
include_once "../config.php";

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? "";
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "UPDATE organizer SET password = ? WHERE oid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $hash, $oid); 
    $stmt->execute();
    $message = "Your password has been changed.";
}

?>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/changepath.css">
    <link rel="icon" href="../favicon.ico">
    <title>Change Password</title>


</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h1>Change Password</h1>
    <div id="message"><?=$message?></div>
<section id="changepath">
<p class="japan">
    パスワードには漢字やひらがな、全角カナ、記号などが利用可能です。<br />
    あなたが覚えておきやすい組み合わせで入力可能になります。<br />
    例）古田係長（５４歳）通称：TAKO なんでタコやねん！<br />
    この組み合わせなら脅威と言われている量子コンピュータを使っても解読できません。
</p>
<p class="english">
    "You can use Multi-byte characters (Kanji/Kana). This exponentially increases the entropy, creating a defense that stays secure even against future 
 quantum computing threats."
</p>

<small>
Note: Multibyte characters provide an incredibly high level of entropy that outsmarts even quantum brute-force.
</small>

<div id="form">
    <form method="POST">
    <h2>New Password</h2>
    <input type="text" name="password" value="" placeholder="8+ chars (Alphanumeric, Symbols, or Multi-byte)" />
    <button type="submit" class="btn" name="submit">Change</button>
    </form>
</div>
</section>
</div>
</main>
<?php include_once 'footer.php'; ?>
</body>
</html>
