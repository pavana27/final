<?php
//	session_start();
//	require_once("config.php");
	$N = $_POST["inputMatrixSize"];
	$P = $_POST["inputNumberOfHosts"];
	$G = $_POST["inputGranularity"];

//	$outputfile = "output".rand().".txt";
//	$output = shell_exec("./launchMMaster.sh $N $G $P $outputfile  2>&1");
	
//	print('sent ./launch 6727 ./../app/matrix/mmaster ' .$N.' ' .$G. ' ' .$P);
//	print("<br/>");
//	var_dump($output);
	//print($outputfile);
//	sleep(30);
	for($x = 0; $x < $P; $x++){
		sleep(1);
		$output1 = shell_exec("./launchMWorker.sh $N $G  2>&1");
		print('sent ./launch 6727 ./../app/matrix/mworker ' .$G);
		print("<br/>");
//		sleep(3);
//		var_dump($output1);
	}
	
	
?>
