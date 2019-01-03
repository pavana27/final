<?php
	session_start();
	/* release this code for content protection
	if ($_SESSION["RegState"] != 4) {
		$_SESSION["Message"] = "Please login first.";
		header("location:index.php");
		exit();
	} 
	*/ 
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="js/favicon.ico">

    <title>CIS5512 Final</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/dashboard.css" rel="stylesheet">
	<link href="css/lab4.css" rel="stylesheet">
  </head>

  <body>
    <nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
      <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="#">Group ID</a>
      <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
      <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
          <a class="nav-link" href="php/clearAll.php">Sign out</a>
        </li>
      </ul>
    </nav>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link active" href="#">
                  <span data-feather="home"></span>
                  Dashboard <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="book-open"></span>
                  Processors
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="book"></span>
                  Networks
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="crosshair"></span>
                  Run
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="bar-chart-2"></span>
                  Results
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="layers"></span>
                  Team Report
                </a>
              </li>
            </ul>

            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>Team Members</span>
              <a class="d-flex align-items-center text-muted" href="#">
                <span data-feather="plus-circle"></span>
              </a>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Member1 Report
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Member2 Report
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Member3 Report
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">
                  <span data-feather="file-text"></span>
                  Member4 Report
                </a>
              </li>
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
		  <h2>Processor Resources</h2>
          <div class="table-responsive">
          <?php 
            $output = shell_exec('lscpu');
            $output = explode("\n", $output);
            foreach ($output as $line) {
                $lineItems = explode(":", $line);
                if(strpos($lineItems[0], "CPU(s)") === 0){
                    $cpus = $lineItems[1];
                }
                
                if(strpos($lineItems[0], "Byte Order") === 0){
                    $byteOrder = $lineItems[1];
                }
                
                if(strpos($lineItems[0], "Model name") === 0){
                    $modelName = $lineItems[1];
                }
                
                if(strpos($lineItems[0], "CPU MHz") === 0){
                    $mhz = $lineItems[1];
                }
                
                if(strpos($lineItems[0], "BogoMIPS") === 0){
                    $bogoMips = $lineItems[1];
                }
            }
          ?>
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>CPU(s)</th>
                  <th>Byte Order</th>
                  <th>Model</th>
                  <th>MHZ</th>
                  <th>BogoMIPS</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><?php echo $cpus; ?></td>
                  <td><?php echo $byteOrder; ?></td>
                  <td><?php echo $modelName; ?></td>
                  <td><?php echo $mhz; ?></td>
                  <td><?php echo $bogoMips; ?></td>
                </tr>
              </tbody>
            </table>
          </div>
          <h2>Network Resources</h2> <a id="net-refresh" href="#"><span data-feather="bar-chart-2"></span>(Refresh)</a>
          <div class="table-responsive"> 
            <table class="table table-striped table-sm">
              <thead>
                <tr>
                  <th>Interface</th>
                  <th>Receive Bytes</th>
                  <th>Errs</th>
                  <th>Drop</th>
                  <th>Transmit Bytes</th>
                  <th>Errs</th>
                  <th>Drop</th>
                </tr>
              </thead>
              <tbody id="net-results">
              	<?php include 'php/network.php';?>
              </tbody>
            </table>
          </div>
          <h2 class="h2" >Run Application</h2>
		  <div class="card" style="width: 500px;">
			<div class="card-header">
				Parallel Dense Matrix Multiplication
			</div>
			<div class="card-body"> 
				<form action="#" method="get" class="form-signin" id="form-acan">
					<div class="row">
						<input type="number" id="inputMatrixSize" name="inputMatrixSize" class="col form-control" placeholder="matrix size(N)" required autofocus>
						<!-- <input type="number" id="inputMatrixSize2" class="col form-control" placeholder="ending size">
						<input type="number" id="inputMatrixStep" class="col form-control" placeholder="skipping size"> -->
					</div>
					<div class="row">
						<input type="number" id="inputNumberOfHosts" name="inputNumberOfHosts" class="col form-control mt-2" placeholder="number of hosts(P)" required>
						<!-- <input type="number" id="inputNumberOfHosts2" class="col form-control mt-2" placeholder="ending hosts"> -->
					</div>
				  <input type="number" id="inputGranularity" name="inputGranularity"  class="form-control mt-2" placeholder=" processing granularity (Hint: >1 and << N/P) " required autofocus>
				  <button id="shell-btn-id" type="submit" class="btn btn-lg btn-primary btn-block mt-2" type="submit">Run</button>
				</form>
			</div> 
		  </div>
		  <div id="resultCard" class="card" style="width: 500px;">
			<div class="card-header">
				Runtime	Monitor
			</div>
			<div id="app-stdout" class="card-body">

			</div>
		  </div>
          <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
            <h1 class="h2">Performance Results</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
              <div class="btn-group mr-2">
                <button class="btn btn-sm btn-outline-secondary">Share</button>
                <button class="btn btn-sm btn-outline-secondary">Export</button>
              </div>
              <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
                <span data-feather="calendar"></span>
                Scalability Charts
              </button>
            </div>
          </div>
	  <div>
		<button id="plot1" class="btn btn-primary btn-block">Performance Plot</button>
          	<canvas class="my-4" id="myChart" width="900" height="380" style="display: none; height: 449px; width: 1068px;"></canvas>
	  </div>
	  <br/>
	  <div>
		<button id="plot2" class="btn btn-primary btn-block">MFLOPS Plot</button>
		<canvas class="my-4" id="myChart2" width="900" height="380" style="display: none; height: 449px; width: 1068px;"></canvas>
	  </div>
	  <div>
		<br/>
		<br/>
	  </div>
        </main>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- <script src="js/jquery-3.2.1.slim.min.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.3.1.js" integrity="sha256-2Kok7MbOyxpgUVvAk/HJ2jigOSYS2auK4Pfzbm7uH60=" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="js/jquery-slim.min.js"><\/script>')</script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <!-- Icons -->
    <script src="js/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <!-- Graphs -->
    <script src="js/Chart.min.js"></script>
    <script>
      var ctx = document.getElementById("myChart");
   /*   var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ["2", "3", "4", "5", "6", "7"],
          datasets: [{
			label: 'Elapsed Time(seconds) vs. Processors [N=9000]',
            data: [47.53, 35.8, 33.93, 33.13, 31.01, 30.2], 
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: true,
			labels: {
				fontColor: 'rgb(255,99,132)'
			}
          }
        }
      });*/
	  var ctx2 = document.getElementById("myChart2");
/*      var myChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
          labels: ["2", "3", "4", "5", "6", "7"],
          datasets: [{
			label: 'MFLOPS vs. Processors [N=9000]',
            data: [15339, 20345, 21483, 22003, 23489, 24092],
            lineTension: 0,
            backgroundColor: 'transparent',
            borderColor: '#007bff',
            borderWidth: 4,
            pointBackgroundColor: '#007bff'
          }]
        },
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: true,
			labels: {
				fontColor: 'rgb(255,99,132)'
			}
          }
        }
      });*/
    </script>
    <script>
        $(document).ready(function(){
            	$('#net-refresh').on('click', function(event) {
            		$.get( "php/network.php", function( data ) {
        		  //console.log( "Data Loaded: " + data );
        		  $('#net-results').html(data);
        		});
        	});

        	$('#form-acan').on('submit', function(event) {
            		event.preventDefault();
			var $inputs = $('#form-acan :input');
			var values = {};
    			$inputs.each(function() {
        			values[this.name] = $(this).val();
    			});
			// console.log(values);
			$.ajax({
				method: "POST",
				url: "php/runACAN.php",
				data: values,
				cache: false
			}).done(function(data){
				$('#app-stdout').html(data);
				$.ajax({
                                	method: "POST",
                                	url: "php/runACAN1.php",
                                	data: values,
                                	cache: false
                        	}).done(function(data){
					$('#app-stdout').append(data);
				});
			});
        	});
	
		var plot1State = 0;	
		$('#plot1').on('click', function(event){
			if(plot1State == 0){
				$.getJSON("php/plotdata.php?plotType=1", function(data){
					plot1State = 1;
					$("#myChart").show();
					var myChart = new Chart(ctx, {
        					type: 'bar',
        					data: {
          						labels: data.processors,
          						datasets: [{
                        					label: 'Elapsed Time(seconds) vs. Processors [N=9000]',
            							data: data.elapsedTime, 
            							lineTension: 0,
            							backgroundColor: 'transparent',
            							borderColor: '#007bff',
            							borderWidth: 4,
            							pointBackgroundColor: '#007bff'
          						}]
        					},
        					options: {
          						scales: {
            							yAxes: [{
             	 							ticks: {
                								beginAtZero: true
              								}
            							}]
          						},
          						legend: {
            							display: true,
                        					labels: {
                                					fontColor: 'rgb(255,99,132)'
                        					}
          						}					
        					}
      					});
				});
				$("#plot1").html("Reset");	
			} else {
				$("#plot1").html("Performance Plot");
				plot1State = 0;
				$("#myChart").hide();
			}
		
		});
		var plot2State = 0;
		$('#plot2').on('click', function(event){
			if(plot2State == 0){
				plot2State = 1;
				$('#plot2').html("Reset");
                        	$.getJSON("php/plotdata.php?plotType=2", function(data){
					$("#myChart2").show(); 
					var myChart2 = new Chart(ctx2, {
        					type: 'line',
        					data: {
          						labels: data.processors,
          						datasets: [{
                        					label: 'MFLOPS vs. Processors [N=9000]',
            							data: data.MFLOPS,
            							lineTension: 0,
            							backgroundColor: 'transparent',
            							borderColor: '#007bff',
            							borderWidth: 4,
           							pointBackgroundColor: '#007bff'
          						}]
        					},
        					options: {
          						scales: {
            							yAxes: [{
              								ticks: {
                								beginAtZero: true
              								}
            							}]
          						},
          						legend: {
            							display: true,
                        					labels: {
                                					fontColor: 'rgb(255,99,132)'
                        					}
          						}
        					}
      					});	
				});
			} else {
                        	$("#plot2").html("MFLOPS Plot");
                        	plot2State = 0;
                        	$("#myChart2").hide();
                	}
		});
		
        });
    </script>
  </body>
</html>
