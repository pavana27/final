<?php
	session_start();
	require_once("config.php");
	$Email = $_POST["Email"];
	$Password = md5($_POST["Password"]);
	print "Login ($Email) ($Password) before MD5(".$_POST["Password"].") <br>";

	$con = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
	if (!$con) {
		$_SESSION["RegState"] = -1;
		$_SESSION["Message"] = "Connection failure: ".mysqli_connect_error();
		header("location:../index.php");
		exit();
	}
	print "DB connected <br>";
	$query = "Select * from Visitors where Password='$Password' and Email = '$Email';";
	$result = mysqli_query($con, $query);
	if (!$result) {
		$_SESSION["RegState"] = -7;
		$_SESSION["Message"] = "Login query failed: ".mysqli_error($con);
		header("location:../index.php");
		exit();
	}
	print "Query done no error. nrows=".mysqli_num_rows($result)." <br>";
	if (mysqli_num_rows($result) != 1) {
		$_SESSION["RegState"] = -8;
		$_SESSION["Message"] = "Either email or password not match";
		header("location:../index.php");
		exit();
	}
	print "Logged in <br>";
	$_SESSION["RegState"] = 4; // Lock is open
	$_SESSION["Message"] = "Login success";
	header("location:../dashboard.php");
	exit();
?> 