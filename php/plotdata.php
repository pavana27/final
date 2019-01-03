<?php
	header('Content-Type: application/json');
	session_start();
        require_once("config.php");
        $con = mysqli_connect(SERVER,USER,PASSWORD,DATABASE);
	$plot_id = $_GET['plotType'];
	if($plot_id == 1){ 
		$query = "Select ElapsedTime, NumOfHosts from ScalabilityLog";
        	$result = mysqli_query($con, $query);
//	if(mysql_num_rows($result) > 0){
		$elapsedTime = array();
		$processors = array();
		while($data = $result->fetch_assoc()){
			//while($row = mysql_fetch_array($result, MYSQL_ASSOC))
			$elapsedTime[] = $data['ElapsedTime'];
			$processors[] = $data['NumOfHosts'];
			//$dataoutput = $dataoutput ."{" ."\"ElapsedTime\":".$data['ElapsedTime']. ", \"NumOfHosts\":". $data['NumOfHosts']."}";
		}
	
		$dataoutput = array('elapsedTime'=>$elapsedTime, 'processors'=>$processors);
	
	//$dataoutput = $dataoutput. "]";
		print json_encode($dataoutput);
	} else {
	 	$query = "Select MFLOPS, NumOfHosts from ScalabilityLog";
                $result = mysqli_query($con, $query);
//      if(mysql_num_rows($result) > 0){
                $MFLOPS = array();
                $processors = array();
                while($data = $result->fetch_assoc()){
                        //while($row = mysql_fetch_array($result, MYSQL_ASSOC))
                        $MFLOPS[] = $data['MFLOPS'];
                        $processors[] = $data['NumOfHosts'];
                        //$dataoutput = $dataoutput ."{" ."\"ElapsedTime\":".$data['ElapsedTime']. ", \"NumOfHosts\":". $data['NumOfHosts']."}";
                }
        
                $dataoutput = array('MFLOPS'=>$MFLOPS, 'processors'=>$processors);
        
        //$dataoutput = $dataoutput. "]";
                print json_encode($dataoutput);
	}	
//	}	
//	mysql_free_result($result);        
?>
