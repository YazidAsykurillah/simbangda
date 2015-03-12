<!DOCTYPE html>

<html lang="en">
	<head>

		<title>Simbangda</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Anggaran Daerah Jawa Tengah" />
	    <meta name="author" content="Asykurillah" />
	    
	    <!-- LOAD CSS FILES -->
	    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
	    <link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
	    <link href="css/jquery-ui.theme.css" rel="stylesheet" type="text/css" />
	      

	</head>

	<body>

		<div class="container">

			<div id="dialogModal" title="Hello">
				Welcome to my site
			</div>

			<ul>
				<li id="menu">Parent 1</li>
				<div id="submenu1">
					<p>Sub menu 1</p>
					<p>Sub menu 2</p>
				</div>
			</ul>

		</div>











		<!-- LOAD JS FILES -->
	   	<script src="js/jQuery.js"></script>
	    <script src="js/bootstrap.js"></script>
	   	<script src="js/jquery-ui.js"></script>

	    <script type="text/javascript">
	    	$(function(){
	    		$('#dialogModal').dialog();
	    	});

	    	$(function(){
	    		$('#menu1').click(function(){
	    			$('#submenu1').toggle();
	    		});
	    	});
	    </script>
	    

	    

	</body>


</html>
