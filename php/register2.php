<?php
	session_start();
    	require_once("config.php");
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;

	require '../PHPMailer-master/src/Exception.php';
	require '../PHPMailer-master/src/PHPMailer.php';
	require '../PHPMailer-master/src/SMTP.php';
	
    	$FirstName=$_GET["FirstName"];
    	$LastName=$_GET["LastName"];
    	$Email=$_GET["Email"];
	print "FN=$FirstName LN=$LastName <br>";
	print "Email=$Email <br>";
	print date('Y-m-d h:i:s');
	$con = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
	if (!$con) {
		$_SESSION["Message"]="Database Connection Failure: ".mysqli_error($con);
		$_SESSION["RegState"] = -1;
		header("location:../index.php");
		exit();
	}
	print "Connect success <br>";
	$Acode = rand();
	$Rdatetime = date("Y-m-d h:i:s");
	$query = "INSERT INTO Visitors (Email, FirstName,LastName,Rdatetime, Acode) VALUES ('$Email','$FirstName','$LastName','$Rdatetime','$Acode');";
	$result = mysqli_query($con, $query);
	print "Finished query <br>";
	if ($result) {
		// Build authentication email
		$mail= new PHPMailer(true);
		try { 
			$mail->SMTPDebug = 2;
			$mail->IsSMTP();
			$mail->Host="smtp.gmail.com";
			$mail->SMTPAuth=true;
			$mail->Username="cis105223053238@gmail.com"; // Do NOT change
			$mail->Password = 'g+N3NmtkZWe]m8"M'; // Do NOT change
			$mail->SMTPSecure = "ssl";
			$mail->Port=465;
			$mail->SMTPKeepAlive = true;
			$mail->Mailer = "smtp";
			$mail->setFrom("tuj44306@temple.edu", "Pavana Pradeep"); // Change to your email and name
			$mail->addReplyTo("tuj44306@temple.edu","Pavana Pradeep"); // Change to your email and name
			print "After setting up mail from and reply <br>"; 	
			$msg = "Please click link to complete registration: http://tec.hpc.temple.edu/~tuj44306/final/php/authenticate.php?Email=$Email&Acode=$Acode";
			$mail->addAddress($Email,"$FirstName $LastName");
			$mail->Subject = "Welcome";
			$mail->Body = $msg;
			$mail->send();
		} catch (phpmailerException $e) {
			$_SESSION["Message"] = "Mailer error: ".$e->errorMessage();
			$_SESSION["RegState"] = -4;
			print "Mail send failed: ".$e->errorMessage;
			header("location:../index.php");
			exit();
		}
	
		$_SESSION["RegState"] = 2;
		$_SESSION["Message"] = "Registration success. Email sent.";
		header("Location:../index.php");
		exit();
	} 
	$_SESSION["RegState"]=1;
	$_SESSION["Message"] = "Registration Failure: ".mysqli_error($con);
	header("Location:../index.php");
	exit();
?>
