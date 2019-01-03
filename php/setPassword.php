<?php
	session_start();
	require_once("config.php");
	$Email = $_POST["Email"];
	$Password = md5($_POST["Password"]);
	print "setPassword ($Email) ($Password) <br>";
	$con = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
	if (!$con) {
		$_SESSION["RegState"] = -1;
		$_SESSION["Message"] = "Connection failure: ".mysqli_connect_error();
		header("location:../index.php");
		exit();
	}
	print "DB connected <br>";

	$query = "update Visitors set Password='$Password' where Email = '$Email';";
	$result = mysqli_query($con, $query);
	if (!$result) {
		$_SESSION["RegState"] = -5;
		$_SESSION["Message"] = "Set password query failed: ".mysqli_error($con);
		header("location:../index.php");
		exit();
	}
	$Acode = rand(); // Get a new Acode
	$Adatetime = date("Y-m-d h:m:s");
	$query = "update Visitors set Acode='$Acode', Adatetime='$Adatetime' where Email = '$Email';";
	$result = mysqli_query($con, $query);
	if (!$result) {
		$_SESSION["Message"] = "Acode update query failure: ".mysqli_error($con);
		$_SESSION["RegState"] = -6;
		header("location: ../index.php");
		exit();
	}
	print "Update Acode worked <br>";
	$_SESSION["RegState"] = 0; // Force to login
	$_SESSION["Message"] = "Password set. Please login";
	header("location:../index.php");
	exit();

?> 
