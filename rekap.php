<?php
	error_reporting(0);
	include"scripts/connect.php";
	$selectedYear = '';
	if(isset($_GET['tahun']) && $_GET['tahun']!=""){
		$selectedYear.= $_GET['tahun'];
	}
	else{
		$selectedYear.='2014';
	}

	$sumTotalFisik = array();   //inisiasi total fisik sebagai array;
	
	$sumTotalSpj = array();
	
	//$tblKegiatan = '';
	
	$sqlKegiatan = "SELECT 
					kegiatan.id_kegiatan,kegiatan.id_instansi,kegiatan.nama_kegiatan,kegiatan.jumlah_anggaran,kegiatan.kontrak_nilai,kegiatan.non_kontrak_nilai,
					instansi.nama_instansi
					FROM kegiatan
					INNER JOIN instansi
					ON kegiatan.id_instansi=instansi.id_instansi
					WHERE kegiatan.ta = $selectedYear
					ORDER BY kegiatan.id_kegiatan
					";

	$resultsqlKegiatan = $db->query($sqlKegiatan);
	if($resultsqlKegiatan->num_rows){					//terdapat result
		while($rowKegiatan = $resultsqlKegiatan->fetch_object()){

			$totalKontrak = $rowKegiatan->kontrak_nilai+$rowKegiatan->non_kontrak_nilai;
			$jumlahAnggaran = $rowKegiatan->jumlah_anggaran;
			//Block Realisasi fisik per kegiatan
			$sqlRealisasiFisik = "SELECT SUM(realisasi_fisik) AS rf FROM kegiatan_bulanan WHERE id_kegiatan=$rowKegiatan->id_kegiatan";
			$getTotalRealisasiFisik = $db->query($sqlRealisasiFisik);
			$rowTotalRealisasiFisik = $getTotalRealisasiFisik->fetch_object();
			$totalRealisasiFisik = $rowTotalRealisasiFisik->rf;
			//END Block Realisasi fisik per kegiatan

			//Block prosentase per kegiatan
			$prosentasePerKegiatan = ($totalRealisasiFisik/$totalKontrak)*100;
			//END Block prosentase per kegiatan

			//Block Total Fisik
			$totalFisik = ($prosentasePerKegiatan/100)*$jumlahAnggaran;
			$sumTotalFisik[]= $totalFisik;
			//END Block total fisik

			//Block Jumlah SPJ
			
			$sqlSpj = "SELECT SUM(jumlah_spj) AS js FROM kegiatan_bulanan WHERE id_kegiatan=$rowKegiatan->id_kegiatan";
			$getTotalSpj = $db->query($sqlSpj);
			$rowTotalSpj = $getTotalSpj->fetch_object();
			$totalSpj= $rowTotalSpj->js;
			$sumTotalSpj[] = $totalSpj;

			//END Block Jumlah SPJ
		}
	}
	else{
		$tblKegiatan.='<tr><td colspan="2">Tidak ada data yang ditampilkan</td></tr>';
	}

	
?>
<?php
	
	//Blok Anggaran
	//. Menghitung anggaran total / Per tahun
	$dpl = $db->query("SELECT SUM(`jumlah_anggaran`) as `totalAnggaran` FROM kegiatan WHERE ta='$selectedYear'");

	$rowAnggaran = $dpl->fetch_assoc();
	$totalAnggaran =$rowAnggaran['totalAnggaran'];
	$totalAnggaranTampil =str_replace(".", ",", $totalAnggaran/1000000000000);			// Tampilkan pagu dalam orde Triliyun


	$fisik = round(array_sum($sumTotalFisik)/$totalAnggaran*100,2);
	$sisaFisik = 100 - $fisik;

	//Blok SPJ
	$spjPertahun = array_sum($sumTotalSpj);				
	$spjPertahunTampil = str_replace(".", ",", $spjPertahun/1000000000000);		//Tampilkan spj dalam orde triliyun
	$realisasiKeuangan = ($spjPertahun/$totalAnggaran)*100;
	$sisaRealisasiKeuangan = 100-$realisasiKeuangan;

	//END Block SPJ

?>

<?php 	
		//Blok option tahun.
		//Utk menampilkan pilihan tahun.

		$optionTahun = '';
		$sqlTahun = "SELECT DISTINCT ta FROM kegiatan WHERE ta !='NULL' ORDER BY ta DESC";
		$resTahun = $db->query($sqlTahun);
		while($rowTahun = $resTahun->fetch_object()){

			$optionTahun.='<option value='.$rowTahun->ta.'>'.$rowTahun->ta.'</option>';
		}

		//END Block Option tahun.
?>

<!DOCTYPE html>

<html lang="en">
	<head>

		<title>Simbangda</title>

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="description" content="Anggaran Daerah Jawa Tengah" />
	    <meta name="author" content="Asykurillah" />
	    
	    <!-- LOAD CSS FILES -->
	    <link href="css/jquery-ui.css" rel="stylesheet" type="text/css" />
	    <link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
	    <link href="css/style.css" rel="stylesheet" type="text/css" />
	    <link href="css/dataTables.css" rel="stylesheet" type="text/css" />
	    <link href="css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
	    <style type="text/css">
	    	
	    	.tree{
	    		padding: 24px;
	    	}

	    	.ui-datepicker-calendar {
    			display: none;

			}​

	    </style>

	</head>

	<body>
		<?php 										//sedot template navbar
			include_once"template_navbar.php"; 
		?>
		<div class="container">

			<div class="page-header">

				<center>
					<h3>
						REKAPITULASI SIMBANGDA PROVINSI JAWA TENGAH TAHUN <?php echo $selectedYear;?>
					</h3>
				</center>

			</div>

			<div class="row">
				<div class="col-md-4">
					<form class="form-inline" method="GET" name="form_tahun" id="form_tahun" action="">
						<div class="form-group">
							<label for="tahun">Tahun</label>
							<select name="tahun" class="form-control" id="tahun">
								<?php
									//Blok menampilkan opsi tahun yang terseleksi.
									$selectedOption = '';
									if(isset($_GET['tahun']) && $_GET['tahun']!=""){					//Ada tahun yang terseleksi, tetapkan pilihan tahun sesuai tahunyang dipilih.
										$selectedOption.= '<option value='.$_GET['tahun'].'>'.$_GET['tahun'].'</option>';
									}
									else{					//Tidak ada tahun yang dipilih, tampilkan opsi pilih tahun.

										$selectedOption.='<option value="">--Pilih Tahun--</option>';
									}
									echo $selectedOption;
								?>
								
								<?php echo $optionTahun; ?>
								
							</select>
							<!-- <input type="text" name="tahun" id="tahun" class="form-control input-sm"> -->
						</div>
						<div class="form-group">
						    <label for=""><p></p></label>
						    <button class="btn btn-sm btn-primary form-control" type="submit">
						    	<i class="glyphicon glyphicon-play"></i>&nbsp;Lihat
						    </button>
						</div> 
					</form>
				</div>
			</div>
			<hr/>

			<div class="alert alert-danger">
				<div class="row">
				
					<div class="col-sm-3">
						<h3>
							<strong>Pagu : <?php echo substr($totalAnggaranTampil,0,4); ?> T</strong>
						</h3>
					</div>
					<div class="col-sm-3">
						<h3>
							<strong>
								SPJ : <?php echo substr($spjPertahunTampil,0,4) ;?> T
							</strong>
						</h3>
					</div>
					
					<div class="col-sm-3">
						<h3>
							<strong>
								Serapan : <?php echo round($realisasiKeuangan,1)." %"; ?>
							</strong>
						</h3>
					</div>
					<div class="col-sm-3">
						<h3>
							<strong>
							Fisik : <?php echo $fisik." %"; ?>
							</strong>
						</h3>
					</div>

				</div>
			</div>

			<hr/>
			<div class="row">
				<div class="col-md-12">

					<div class="col-md-4" style="width:200px;">

					</div>
					<div class="col-md-4" id="chartFisik">
						
				        
					</div>

					<div class="col-md-4" id="chartKeuangan">
						
					</div>
					
				</div>

			</div>

			<hr/>

			<div class="row">

				<div class="col-md-12">
					
			        <div class="panel panel-primary" style="min-height:500px;">
			            <div class="panel-heading">
			            	<h3 class="panel-title">
			            		Daftar kegiatan pada tiap instansi di tahun <?php echo $selectedYear;?>
			            	</h3>
			            </div>

			            <div class="panel-body">
			            	

			            	<div class="tree" id="dataKegiatan">


			            	</div>
			            	
			            	<center id="loadingImage">
			            		<img src="images/loadingsnake.GIF">
			            		<p class="alert alert-info">Mohon tunggu, sedang mengumpulkan data kegiatan...</p>
			            	</center>

				        </div>
			        </div>

				</div>

			</div>
	

		</div>






		<!-- LOAD JS FILES -->
	   	<script src="js/jQuery.js"></script>
	   	<script src="js/jquery-ui.js"></script>
	    <script src="js/bootstrap.js"></script>
	    <script src="js/highcharts.js"></script>
	    <script src="js/highcharts-3d.js"></script>
	    <script src="js/dataTables.js"></script>
	    <script src="js/dataTables.responsive.js"></script>


	   

	  	<script type="text/javascript">

	  		$(document).ready(function(){

	  			var ta = <?php echo $selectedYear;?>;

	  			$.ajax({
	  				url : 'scripts/server_side.php',
	  				type : 'POST',
	  				data : 'tahun='+ta,
	  				beforeSend:function(){
	  					$('#loadingIndikator').show();
	  				},
	  				success:function(response){
	  					$('#dataKegiatan').html(response);
	  					$('#loadingImage').fadeOut("fast");
	  					//callDataTable();
	  					tree();
	  					
	  				}
	  			});
	  		});

	  		function callDataTable(){
	  			$('#tblKegiatan').dataTable();
	  		}

	  	</script>
	  	
	  	<script type="text/javascript">  //Tree js

	  		 function tree( ) {
					$( '.tree li' ).each( function() {
							if( $( this ).children( 'ul' ).length > 0 ) {
									$( this ).addClass( 'parent' );     
							}
					});
					
					$( '.tree li.parent > a' ).click( function( ) {
							$( this ).parent().toggleClass( 'active' );
							$( this ).parent().children( 'ul' ).slideToggle( 'fast' );

							//$('#all').show();
					});
					
					$( '#all' ).click( function() {
						
						$( '.tree li' ).each( function() {
							$( this ).toggleClass( 'active' );
							$( this ).children( 'ul' ).slideToggle( 'fast' );
						});
					});
					
					$( '.tree li' ).each( function() {
							//$( this ).toggleClass( 'active' );
							//$( this ).children( 'ul' ).slideToggle( 'fast' );
					});
				}

	  	</script>

	    <script>
	    //Block Chart Realisasi Fisik

	    	$(function () {

			    // Make monochrome colors and set them as default for all pies
			    Highcharts.getOptions().plotOptions.pie.colors = (function () {
			        var colors = ['#910000','#f28f43', '#77a1e5', '#c42525', '#a6c96a'],
			            base = Highcharts.getOptions().colors[0],
			            i;

			        for (i = 0; i < 10; i += 1) {
			            // Start out with a darkened base color (negative brighten), and end
			            // up with a much brighter color
			            colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
			        }
			        return colors;
			    }());

			    // Build the chart
			    $('#chartFisik').highcharts({
			        chart: {
			            plotBackgroundColor: null,
			            plotBorderWidth: null,
			            plotShadow: false,
			            options3d: {
							enabled: true,
			                alpha: 45,
			                beta: 0,
			            }
			        },
			        credits:{
			        	enabled :false,
			        },
			        title: {
			            text: 'Realisasi Fisik tahun '+<?php echo $selectedYear;?>
			        },
			        tooltip: {
			            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
			        },
			        plotOptions: {
			            pie: {
			                allowPointSelect: true,
			                cursor: 'pointer',
			                dataLabels: {
			                    enabled: true,
			                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
			                    style: {
			                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
			                    }
			                },
			                depth: 25
			            }
			        },
			        series: [{
			            type: 'pie',
			            name: 'Realisasi Fisik',
			            data: [
			                ['Penggunaan', <?php echo $fisik; ?>],
		                    ['Sisa', <?php echo $sisaFisik; ?>]
			            ]
			        }]
			    });
			});

		//END Block Chart Realisasi Keuangan
	    </script>

	    <script>
	    //Block Chart Realisasi Keuangan,
	    	$(function () {
		        $('#chartKeuangan').highcharts({
		            chart: {
		                plotBackgroundColor: null,
		                plotBorderWidth: null,
		                plotShadow: false,
		                options3d: {
							enabled: true,
			                alpha: 45,
			                beta: 0,
			            }
		            },
		             credits:{
			        	enabled :false,
			        },
		            title: {
		                text: 'Realisasi Keuangan tahun '+<?php echo $selectedYear; ?>
		            },
		            tooltip: {
		                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
		            },
		            plotOptions: {
		                pie: {
		                    allowPointSelect: true,
		                    cursor: 'pointer',
		                    dataLabels: {
		                        enabled: true,
		                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
		                        style: {
		                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
		                        }
		                    },
		                    depth: 25
		                }
		            },
		            series: [{
		                type: 'pie',
		                name: 'Realisasi Keuangan',
		                data: [
		                    
		                    ['Penggunaan', <?php echo $realisasiKeuangan;?>],
		                    ['Sisa', <?php echo $sisaRealisasiKeuangan;?>]
		                ]
		            }]
		        });
		    });

		//END Block Chart Realisasi Keuangan
	    </script>

	  
	    <script type="text/javascript">	//Block Get detail

	    	function getDetail(par){

	    		var id_instansi = par;
	    		var selectedYear = <?php echo $selectedYear;?>;
	    		var dataPost = 'id_instansi='+id_instansi+'&selectedYear='+selectedYear;

	    		$.ajax({

	    			url		:'scripts/getDetail.php',
	    			type	:'POST',
	    			data 	: dataPost,
	    			beforeSend :function(){},
	    			success : function(response){
	    				$('#tblInstansi_'+id_instansi).append(response);
	    				$('#detailViewer_'+id_instansi).remove();
	    				$('#substituent_'+id_instansi).show();
	    				$('#tblInstansi_'+id_instansi).dataTable({
	    					responsive:true
	    				});
	    				$('#loadingImage').hide();
	    			}
	    		});
	    	}
	    </script>

	    <script type="text/javascript">
	    	$('#form_tahun').submit(function(e){
	    		var ta = $('#tahun').val();
	    		if(ta == ""){
	    			alert("Anda belum memilih tahun");
	    			e.preventDefault();
	    		}

	    	});
	    </script>
	    <script type="text/javascript">

	    	$('#tahun').datepicker({

	    		showOn: "button",
	    		buttonImage: "images/icon-calendar.gif",
     			changeYear: true,
     			dateFormat: 'yy',

	    	});
	    </script>


	</body>


</html>
