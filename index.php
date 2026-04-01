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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="./common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./common/css/style.css">
    <link rel="stylesheet" type="text/css" href="./common/css/index.css">
    <link rel="icon" href="./favicon.ico">
    <title>3DVenue: Open Source Virtual Exhibit Engine (MIT Licensed)</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<div id="eyecatch">
    <div class="inner">
        <div class="overlay">

        <h1>Make your homepage <br />
            the gateway to <br />
            your virtual exhibition.
        </h1>
            <p>
            A new opportunity for small businesses.<br>
            A low‑impact way to turn your homepage into an exhibition booth.<br>
            An accessible, next‑generation platform open to everyone.
        </p>
        </div>
        <div id="eycatchCenter">
        <a href="acount.php">Exhibitor Registration</a>
        <a href="https://github.com/3dvenue/3Dvenue-mit-jp" target="_blank">Clone from GitHub</a>
        </div>
     </div>
</div>

<main>
    <div class="inner">

    <section id="infomation">
    <h2>What's New</h2>
        <div id="info">
        <?php
            $sql = "SELECT * FROM infomation WHERE target = 0";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            $published_at = date('F j, Y',strtotime($row['published_at']));
            $title = $row['title'];
            $content = $row['content'];
        ?>
        <details>
            <summary><date><?=$published_at?>:</date><?=$title?></summary>
            <div><?=$content?></div>
        </details>
        <?php } ?>
        </div>
    </section>

<section id="content">
    <h2>Content</h2>
    <div id="features">
        <div><figure><a href="about.php"><img src="./img/about.webp" alt="About 3DVenue"></a></figure></div>
        <div><figure><a href="expo.php"><img src="./img/exhibitors.webp" alt="3DVenue Virtual EXPO"></a></figure></div>
    </div>
</section>

</div>


<section id="venue">
    <div class="inner">
    <h2>Current Exhibitions</h2>
        <div>
        <ul id="venues">
        <?php
            $sql = "SELECT * FROM venue WHERE public = 1 ORDER BY id DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
            $id = $row['id'];
        ?>
        <li><figure><a href="./expo/<?=$id?>/" target="_blank"><img src="./que/<?=$id?>/bana.webp"></a></figure></li>
        <?php } ?>
        </ul>
        </div>
    </div>
</section>

</main>
<?php include_once('footer.php')?>
</body>
</html>