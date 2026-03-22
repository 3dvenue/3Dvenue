<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
*/

include_once "auth.php";
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = $_GET['i'];

$venueLogPath = '../expo/'.$id.'/venue.log';
$accessLogPath = '../expo/'.$id.'/access.log';

$venue = [];
$access = [];

if (file_exists($venueLogPath)) {
    $venue = file($venueLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

if (file_exists($accessLogPath)) {
    $access = file($accessLogPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
}

function parseLog($logLines) {
    $daily = [];
    $hourly = [];

    foreach ($logLines as $line) {
        // "2026-02-17 03:42:06 153.240.198.6"
        $parts = explode(' ', $line);
        if (count($parts) < 2) continue;

        $datetime = $parts[0] . ' ' . $parts[1];
        $timestamp = strtotime($datetime);
        if (!$timestamp) continue;

        $date = date('Y-m-d', $timestamp);
        $hour = date('H', $timestamp);

        // 日別
        if (!isset($daily[$date])) $daily[$date] = 0;
        $daily[$date]++;

        // 時間別
        if (!isset($hourly[$hour])) $hourly[$hour] = 0;
        $hourly[$hour]++;
    }

    return [
        'daily' => $daily,
        'hourly' => $hourly
    ];
}

// venue と access を別々に解析
$venueParsed  = parseLog($venue);
$accessParsed = parseLog($access);

// JS に渡すため JSON 化
$venueDailyJson  = json_encode($venueParsed['daily']);
$venueHourlyJson = json_encode($venueParsed['hourly']);

$accessDailyJson  = json_encode($accessParsed['daily']);
$accessHourlyJson = json_encode($accessParsed['hourly']);



}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../common/css/base.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css">
    <link rel="stylesheet" type="text/css" href="./css/analyze.css">
    <link rel="icon" href="../favicon.ico">
    <title>Access Analysis</title>
</head>
<body>
<main>
	<div class="inner">
		<h1>Access Analysis</h1>
		<section>
		<h2>Entrance Analysis</h2>

		<div class="kei">Total: <?=count($access)?> hits</div>


		<canvas id="accessDaily"></canvas>
		<canvas id="accessHourly"></canvas>

		<h2>Virtual Venue Analysis</h2>

		<div class="kei">Total: <?=count($venue)?> hits</div>

		<canvas id="venueDaily"></canvas>
		<canvas id="venueHourly"></canvas>


		</section>
	</div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const venueDaily  = <?php echo $venueDailyJson; ?>;
const venueHourly = <?php echo $venueHourlyJson; ?>;

const accessDaily  = <?php echo $accessDailyJson; ?>;
const accessHourly = <?php echo $accessHourlyJson; ?>;

// venue 日別
new Chart(document.getElementById('venueDaily'), {
    type: 'bar',
    data: {
        labels: Object.keys(venueDaily),
        datasets: [{
            label: 'Venue Daily Access',
            data: Object.values(venueDaily),
            backgroundColor: 'rgba(54, 162, 235, 0.6)'
        }]
    }
});

// venue 時間別
new Chart(document.getElementById('venueHourly'), {
    type: 'line',
    data: {
        labels: Object.keys(venueHourly),
        datasets: [{
            label: 'Venue Hourly Access',
            data: Object.values(venueHourly),
            borderColor: 'rgba(255, 159, 64, 0.8)',
            fill: false
        }]
    }
});

// access 日別
new Chart(document.getElementById('accessDaily'), {
    type: 'bar',
    data: {
        labels: Object.keys(accessDaily),
        datasets: [{
            label: 'Access Daily Access',
            data: Object.values(accessDaily),
            backgroundColor: 'rgba(75, 192, 192, 0.6)'
        }]
    }
});

// access 時間別
new Chart(document.getElementById('accessHourly'), {
    type: 'line',
    data: {
        labels: Object.keys(accessHourly),
        datasets: [{
            label: 'Access Hourly Access',
            data: Object.values(accessHourly),
            borderColor: 'rgba(153, 102, 255, 0.8)',
            fill: false
        }]
    }
});
</script>



</body>
</html>
