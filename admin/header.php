<?php
/*
 3Dvenue - Experiential Space Engine
 Copyright (c) 2026 yoshihiro
 Licensed under MIT (https://opensource.org/licenses/MIT)
 This software is released under the MIT License, see LICENSE.txt
 "Transforming information from browsing to residing."
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('auth.php');
?>
<header>
	<div class="inner">

	</div>
</header>

<nav>
	<div class="inner">
	<ul>
		<li><a href="index.php">Top</a></li>		
		<li><a href="expo.php">Expo</a></li>		
		<li><a href="organizer.php">Organizer</a></li>	
		<li><a href="company.php">Company</a></li>
		<li><a href="logout.php">Logout</a></li>		
	</ul>
	</div>
</nav>