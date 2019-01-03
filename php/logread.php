<?php
	$outputfile = $_GET['logfile'];
	echo shell_exec("tail -1000f apps/matrix/" .$outputfile);
?>
