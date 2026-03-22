<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

session_start();
require_once('auth.php');
require_once "../config.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $target = $_POST['target'];
    $public = $_POST['public'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $published_at = $_POST['published_at'];
echo  $submit = $_POST['submit'];

    if($submit == "add"){
        $sql = "INSERT INTO infomation (title,content,target,public,published_at) VALUES (?,?,?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiis", $title,$content,$target,$public,$published_at);
        $stmt->execute();
    }

    if($submit == "edit"){
        $sql = "UPDATE infomation SET title = ?,content = ?,target = ?,public = ?,published_at = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssiisi", $title,$content,$target,$public,$published_at,$id);
        $stmt->execute();
    }
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/index.css">
    <link rel="icon" href="../favicon.ico">
    <title>Admin Top</title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h1>Official Announcements</h1>

<div id="addNew"><button class="btn" id="new">Add New</button></div>

<?php
$targets = ["Global News", "Exhibitor News", "Organizer News"];
for($i=0; $i<=2; $i++){
?>

<section>

<h2><?=$targets[$i]?></h2>

<div class="info">
<?php
$sql = "SELECT * FROM infomation WHERE target = ? ORDER BY published_at ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $i);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $id = $row['id'];
    $title = $row['title'];
    $content = $row['content'];
    $target = $row['target'];
    $public = $row['public'];
    $published_at = date('Y-m-d',strtotime($row['published_at']));
?>
<div class="infomation" data-target="<?=$target?>" data-public="<?=$public?>" data-id="<?=$id?>">
    <date class="published_at"><?=$published_at?></date>
    <h3 class="title"><?=$title?></h3>
    <div class="content"><?=$content?></div>
</div><?php } ?>

</section>

<?php } ?>

</div>

</div>
</main>

<div id="form">
    <div class="close">&times;</div>
    <form method="POST" class="form">
        <label><span>TITLE</span><input type="text" id="title" name="title" required></label>
        <label><span>BODY</span><textarea id="content" name="content" required></textarea></label>
        
        <label><span>TARGET</span>
            <select name="target" id="target">
                <option value="0">GLOBAL</option>
                <option value="1">EXHIBITOR</option>
                <option value="2">ORGANIZER</option>
            </select>
        </label>
        
        <label><span>STATUS</span>
            <select name="public" id="public">
                <option value="0">PUBLIC</option>
                <option value="1">PRIVATE</option>
            </select>
        </label>
        
        <label><span>DATE</span><input type="date" id="published_at" name="published_at" value="<?php echo date('Y-m-d'); ?>"></label>
        
        <div class="button-area">
            <input type="hidden" id="id" name="id" value="">
            <button type="submit" class="btn" name="submit" id="btn_add" value="add">ADD NEW</button>
            <button type="submit" class="btn" name="submit" id="btn_edit" value="edit">UPDATE</button>
        </div>
    </form>
</div>

<?php include_once '../footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

        $('#new').on('click',function(){
            $('#target').val(0);
            $('#public').val(0);
            $('#published_at').val('<?=date('Y-m-d')?>');
            $('#title,#content,#id').val('');
            $('#form').addClass('active new');
        })

        $('.infomation').on('click',function(){
            let id = $(this).data('id');
            let target = $(this).data('target');
            let public = $(this).data('public');
            let published_at = $(this).find('.published_at').text();
            let title = $(this).find('.title').html();
            let content = $(this).find('.content').html();
            console.log(title);
            console.log(content);
            $('#id').val(id);
            $('#target').val(target);
            $('#public').val(public);
            $('#title').val(title);
            $('#content').val(content);
            $('#published_at').val(published_at);
            $('#form').addClass('active edit');
        })

        $('.close').on('click',function(){
            $('#form').removeClass();
        })

    });
</script>
</body>
</html>
