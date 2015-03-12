<?php
	include_once"../scripts/connect.php";

	if(!isset($_POST['ta']) || $_POST['ta']==""){

		exit("Anda belum menentukan tahun");
		
	}
	$ta = preg_replace('#[^0-9]#', '', $_POST['ta']);
	$insert_kegiatan = "call insert_kegiatan($ta)";
	//$update_sbdana = "call update_sbdana(@ta)";
	$doInsert = $db->query($insert_kegiatan);
	if($doInsert){

		echo "insertKegiatanOk";
		
	}
	else{
		die("CALL Error: ".$db->error);
	}





	
?>