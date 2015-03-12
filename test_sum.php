<?php
include"scripts/connect.php";
	
	$sqlRFisik = "SELECT SUM(realisasi_fisik) as rtf FROM kegiatan_bulanan WHERE id_kegiatan='29463'";
				
	$resultRFisik = $db->query($sqlRFisik);
	$row_resultRFisik = $resultRFisik->fetch_object();
	$total = $row_resultRFisik->rtf;
	echo $total;

?>