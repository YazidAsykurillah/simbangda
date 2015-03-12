<?php
	//error_reporting(0);
	include"scripts/connect.php";
	
	
?>

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
	    <link href="css/dataTables.css" rel="stylesheet" type="text/css" />

	</head>

	<body>
		<?php 										//sedot template navbar
			include_once"template_navbar.php"; 
		?>
		<div class="container">

			<div class="page-header">

				<center>
					<h3>
						Sinkronisasi
					</h3>
				</center>
			</div>

			<div class="row" id="panel_sinkronisasi">

				<div class="col-md-6" id="menu_sinkronisasi">

					<div class="panel panel-default">

					  	<div class="panel-heading">
					    	<h3 class="panel-title">Sinkronisasi</h3>
					  	</div>

					  	<div class="panel-body">
					    	<form name="formSinkron" id="formSinkron" method="POST" action="sinkron/pagu.php">
							  <div class="form-group">
							    <label for="tahun">Tahun</label>
							    <select name="ta" id="tahunPagu" class="form-control input-lg">
							    	<option value="">--Pilih Tahun--</option>
							    	<option value="2015">2015</option>
							    	<option value="2014">2014</option>
							    	<option value="2013">2013</option>
							    	<option value="2012">2012</option>
							    	<option value="2011">2011</option>
							    	<option value="2010">2010</option>
							    	<option value="2009">2009</option>
							    	<option value="2008">2008</option>
							    	<option value="2007">2007</option>
							    </select>
							  </div>							  
							  <button type="submit" class="btn btn-lg btn-primary">
							  	<i class="glyphicon glyphicon-retweet"></i>&nbsp;Sinkronisasikan
							  </button>

							</form>
					  	</div>

					</div>

				</div>

				<div class="col-md-6" id="information">
					<div id="loadingInsertKegiatan" style="display:none;">
						<center>
							<img src="images/loadingsnake.gif" align="center">
							<p class="alert alert-info">Sedang melakukan proses insert kegiatan...</p>
						</center>
					</div>
					<div id="loadingUpdateSbDana" style="display:none;">
						<center>
							<img src="images/loadingsnake.gif" align="center">
							<p class="alert alert-danger">Sedang melakukan proses update SB DANA...</p>
						</center>
					</div>

					<!-- 
					<div class="progress">
					  	<div class="progress-bar progress-bar-info" role="progressbar">
					    	
					  	</div>
					</div> 
					-->
					
				</div>

			</div>

			<hr/>

			<center>
				<div id="loadingTampilData" style="display:none;">
					<center>
						<img src="images/loadingsnake.gif">
						<p class="alert alert-danger">Sedang mengumpulkan data untuk ditampilkan....</p>
					</center>
				</div>
			</center>

			<div class="row" id="panel_tabel">
				

			</div>
			



		</div>







		<!-- LOAD JS FILES -->
	   	<script src="js/jQuery.js"></script>
	    <script src="js/bootstrap.js"></script>
	    <script src="js/dataTables.js"></script>

	   	<script type="text/javascript">
	   		$(document).ready(function(){

	   			$('#formSinkron').submit(function(e){
	   				
	   				var ta = $('#tahunPagu').val();
	   				if(ta == ""){
		   				alert("Anda belum memilih tahun");
		   			}
		   			else{
		   				var conf = confirm("Anda yakin mau melakukan sinkronisasi?");
		   				if(conf == true){
		   					$('#menu_sinkronisasi').hide();				//Sembunyikan menu sinkronisasi
			   				$.ajax({
			   					url		:'sinkron/insert_kegiatan.php',
			   					type	:'POST',
			   					data 	:'ta='+ta,
			   					beforeSend:function(){
			   						$('#loadingInsertKegiatan').show();
			   					},
			   					success	: function(response){
			   						if(response == "insertKegiatanOk"){
			   							
			   							
			   							$('#loadingInsertKegiatan').hide();			//sembunyikan animasi loading insert kegiatan
			   							$('#loadingUpdateSbDana').show();			//Tampilkan animasi loading update sb dana

			   							doUpdateSbDana(ta);	//Panggil fungsi untuk melakukan update sb dana

			   						}
			   						else{
			   							$('#information').html(response);
			   							$('#loadingUpdateSbDana').hide();
			   						}
			   					}
			   				});
			   			}
			   			else{
			   				return false;
			   			}
		   			}

	   				e.preventDefault();
	   			});
	   		});

	   		function doUpdateSbDana(par){
	   			var ta = par;
	   			if(par == ""){
	   				alert("Tahun Kosong...");
	   			}
	   			else{

	   				$.ajax({
	   					url		:'sinkron/update_sb_dana.php',
		   					type	:'POST',
		   					data 	:'ta='+ta,
		   					beforeSend:function(){
		   						$('#loadingUpdateSbDana').show();
		   					},
		   					success	: function(response){
		   						if(response == "updateSbDanaOk"){
		   							
		   							
		   							$('#loadingUpdateSbDana').hide();
		   							
		   							showTable(ta);						//Panggil fungsi untuk emnampilkan tabel

		   						}	
		   						else{

		   							$('#information').html(response);
		   							showTable(ta);						//Panggil fungsi untuk menampilkan tabel
		   						}
		   					}
	   				});
	   			}
	   		}


	   		//Blok Fungsi menampilkan table, setalah fungsi sinkronisasi selesai

	   		function showTable(ta){				

	   			var ta = ta;
	   			var dataPost = 'ta='+ta;
	   			if(ta ==""){
	   				alert("anda belum memilih tahun");
	   			}
	   			else{

	   				$('#panel_sinkronisasi').hide();
	   				$('#panel_tabel').show();

	   				$.ajax({

	   					url : 'sinkron/getTable.php',
	   					type : 'POST',
	   					data : dataPost,
	   					beforeSend : function(){
	   						$('#loadingTampilData').show();
	   					},
	   					success:function(response){

	   						$('#panel_tabel').html(response);
	   						$('#loadingTampilData').hide();
	   						callDataTable();					//Panggil fungsi untuk build data table pada tabel hasil respon.
	   					}

	   				});
	   			}
	   		}

	   		//Fungsi untuk mengkonversi tabel respon ke datatables
	   		function callDataTable(){					

	   			$('#tblResultSinkron').dataTable();
	   		}
	   	</script>


	   	<script type="text/javascript">			//Progress Bar
			/*
	   		$(document).ready(function(){
	   			$(".progress-bar").animate({
				    width: "70%"
				});
	   		});
			*/
	   	</script>
	</body>


</html>
