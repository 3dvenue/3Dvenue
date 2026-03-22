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
    $submit = $_POST['submit'];
    $company = $_POST['company'] ?? "";
    $oname = $_POST['oname'] ?? "";
    $email = $_POST['email'] ?? "";
    $password = $_POST['password'] ?? "";
    $telno = $_POST['telno'] ?? "";
    $zip = $_POST['zip'] ?? "";
    $prefecture = $_POST['prefecture'] ?? "";
    $address1 = $_POST['address1'] ?? "";
    $address2 = $_POST['address2'] ?? "";

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if($submit == "add"){
        $sql = "INSERT INTO organizer (company, oname, email, password, telno, zip, prefecture, address1, address2) VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss",$company, $oname, $email, $hashed_password, $telno, $zip, $prefecture, $address1, $address2);
        $stmt->execute();
    }
}

$sql = "SELECT * FROM organizer";
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$organizers = $result->fetch_all(MYSQLI_ASSOC);

$organizer = "";
$id = 0;
$name = "";

if (isset($_GET['oid']) && is_numeric($_GET['oid'])) {
    $oid = $_GET['oid'];
    $name = "(".$_GET['name'].")";
    $organizer = "organizer";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/organizer.css">
    <link rel="icon" href="../favicon.ico">
    <title>
        <?=$title?>
    </title>
<style type="text/css">
</style>

</head>
<body>
<?php include_once 'header.php'; ?>
<main>
    <div class="inner">
    <h2>Organizer <small><?=$name?></small></h2>


<section id="organizer">
<button id="add" class="button">+</button>
<h3>Organizer</h3>
<table>
    <tr>
        <th>Organization Name</th>
        <th>Contact Person</th>
        <th>Phone Number</th>
        <th>Prefecture</th>
    </tr>
<?php
    $sql = "SELECT * FROM organizer";
    if($organizer == "organizer"){
        $sql = "SELECT * FROM organizer WHERE oid = {$oid}";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $oid = $row['oid'];
        $company = $row['company'];
        $oname = $row['oname'];
        $telno = $row['telno'];
        $prefecture = $row['prefecture'];
?>

<tr class="organizer" data-oid="<?=$oid?>">
        <td class="companyname"><?=$company?></a></td>
        <td><?=$oname?></td>
        <td><?=$telno?></td>
        <td><?=$prefecture?></td>
</tr>
<?php } ?>
</table>

</section>
</div>
</main>
<div id="form">
     <div id="addform">
        <div class="close">&times;</div>
            <form method="post">
            <input type="hidden" name="id" id="id" />
            <label for="company"><span>Organization Name</span><input type="text" name="company" id="company" placeholder="Organization name" required /></label>
            <label for="oname"><span>Contact Person</span><input type="text" name="oname" id="oname" placeholder="Contact Person" required /></label>
            <label for="email"><span>email</span><input type="text" name="email" id="email" placeholder="email" required /></label>
            <label for="password"><span>password</span><input type="text" name="password" id="password" placeholder="password" required /></label>
            <label for="telno"><span>Phone Number</span><input type="text" name="telno" id="telno" placeholder="Phone Number" required /></label>
            <label for="zip"><span>Postal Code</span><input type="text" name="zip" id="zip" placeholder="Postal Code" required /></label>
            <label for="prefecture"><span>prefecture</span><input type="text" name="prefecture" id="prefecture" placeholder="prefecture" required /></label>
            <label for="address1"><span>Address Line 1</span><input type="text" name="address1" id="address1" placeholder="Address Line 1" required /></label>
            <label for="address2"><span>Address Line 2</span><input type="text" name="address2" id="address2" placeholder="Address Line 2" /></label>
            <div id="button"><button type="submit" class="btn" name="submit" id="submit" value="add">Add</button></div>
        </form>
    </div>
</div>


<div id="companyedit">
    <div id="check">
        <div class="close">&times;</div>
        <h3 id="companyname"></h3>
        <p>Access the Organizer's Page.</p>
        <input type="hidden" name="oid" id="oid" />
        <div id="button"><span class="btn" id="jump">Open Organizer</span></div>        
    </div>
</div>

<div id="frame">
        <div class="close">&times;</div>
        <div id="iframetitile">Organizer Page</div>
        <iframe src="../organizer/login.php"></iframe>
</div>

<?php include_once '../footer.php'; ?>
<script src="../common/js/jquery.js"></script>
<script type="text/javascript">
    $(function(){

        const organizers = <?php echo json_encode($organizers); ?>;

        $('#add').on('click', function() {
            $('#form').addClass('active');
        });

        $('#jump').on('click', function() {
            const cname = $('#companyname').text();
            const oid = $('#oid').val();
            $('#frame iframe').attr('src','jump_organizer.php?o=' + oid + '&c='+cname);
            $('#frame').addClass('active');
            $('#companyedit').removeClass();
        });

        $('#check .close').on('click',function(){
            $('#companyedit').removeClass('active');
            $('#companyedit input').val('');
        });

        $('.organizer').on('click', function() {
            $('#companyedit').addClass('active');
            $('#companyedit input').val('');
            const oid = $(this).data('oid');
            const cname = $(this).find('td.companyname').text();
            $('#oid').val(oid);
            $('#companyname').text(cname);
        });

        $('#form .close').on('click',function(){
            $('#form').removeClass();
        });

        $('#frame .close').on('click',function(){
            $('#frame').removeClass('active');           
        })

    });
</script>
</body>
</html>
