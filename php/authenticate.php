<?php
	session_start();
	require_once("config.php");
	$Acode = $_GET["Acode"];
	$Email = $_GET["Email"];
	print "Webdata($Email)($Password)<br>";
	$con = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
	if (!$con) {
		$_SESSION["RegState"] = -1;
		$_SESSION["Message"] = "Connection failure: ".mysqli_connect_error();
		header("location:../index.php");
		exit();
	}
	print "Database connected <br>";

	$Adatetime = date("Y-m-d H:i:s");
	$query = "Select * from Visitors where Email='$Email' and Acode='$Acode';";
	$result = mysqli_query($con, $query);
	print "Query($query) run <br>";
	if (!$result) {
		$_SESSION["RegState"] = -2;
		$_SESSION["Message"] = "Authentication Query Failed: ".mysqli_error($con);
		header("location:../index.php");
		exit();
	}
	if (mysqli_num_rows($result) != 1) {
		$_SESSION["Message"] = "Email or Acode not match.";
		$_SESSION["RegState"] = -3;
		header("location:../index.php");
		exit();
	}
	// Update Visitors and allow for password setup
	$query = "update Visitors set Adatetime='$Adatetime' where Email='$Email';";
	$result = mysqli_query($con, $query);
	if (!$result) {
		$_SESSION["Message"] = "Authentication update query failure: ".mysqli_error($con);
		$_SESSION["RegState"] = -4;
		header("location:../index.php");
		exit();
	}
	$_SESSION["Email"] = $Email;
	$_SESSION["RegState"] = 3;
	$_SESSION["Message"] = "Authentication Success. Please set password";
	header("location:../index.php");
	exit();
?> 