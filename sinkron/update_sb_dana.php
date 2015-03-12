<?php
	include_once"../scripts/connect.php";

	if(!isset($_POST['ta']) || $_POST['ta']==""){

		exit("Anda belum menentukan tahun");
		
	}
	$ta = preg_replace('#[^0-9]#', '', $_POST['ta']);
	
	$update_sbdana = " UPDATE kegiatan 
						INNER JOIN sbdanakeg 
						ON kegiatan.K_KDKEGUNIT = sbdanakeg.KDKEGUNIT 
						SET kegiatan.sd = sbdanakeg.KDDANA
	 					WHERE sbdanakeg.KDTAHAP='2' AND kegiatan.ta=$ta";

	$doUpdate = $db->query($update_sbdana);

	if($doUpdate){


		echo "updateSbDanaOk";

	}

	else{
		
		die("CALL Error: ".$db->error);
	}





	
?>