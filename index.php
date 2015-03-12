
<!DOCTYPE html>

<html lang="en">
	<head>

		<title>Simbangda</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Anggaran Daerah Jawa Tengah" />
	    <meta name="author" content="Asykurillah" />
	    
	    <!-- LOAD CSS FILES -->
	    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
	    <link href="css/style.css" rel="stylesheet" type="text/css" />  

	</head>

	<body>
		<?php 										//sedot template navbar
			include_once"template_navbar.php"; 
		?>
		<div class="container">

			<div class="jumbotron" style="margin-left:auto; margin-right:auto;width:800px;">
		  		<img src="images/logo_jateng.png" class="img-responsive">
			</div>
			<div class="row">
				<div class="col-md-6">

					<a href="rekap.php" class="btn btn-primary btn-lg btn-info pull-right" title="Rekapitulasi">
						Rekapitulasi&nbsp;<img src="images/report.png" style="height:125px;">
					</a>
				</div>
				<div class="col-md-6">
					<a href="sinkronisasi.php" class="btn btn-primary btn-lg btn-danger" title="Sinkronisasi">
						Sinkronisasi&nbsp;<img src="images/sync.jpg" style="height:125px;">
					</a>
				</div>
				<!-- 
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-lg btn-block btn-warning">
						<i class="glyphicon glyphicon-eye-open">&nbsp;Monitoring</i>
					</a>
				</div>
				<div class="col-md-3">
					<a href="#" class="btn btn-primary btn-lg btn-block btn-success">
						<i class="glyphicon glyphicon-folder-open">&nbsp;Report</i>
					</a>
				</div>
				 -->
				
			</div>


		</div>







		<!-- LOAD JS FILES -->
	   	<script src="js/jQuery.js"></script>
	    <script src="js/bootstrap.js"></script>
	    <script src="js/highcharts.js"></script>
	    <script src="js/highcharts-3d.js"></script>
	  

	    
	</body>


</html>
