<?php

	include"../scripts/connect.php";

	

	if(!isset($_POST['ta']) || $_POST['ta']==""){

		exit("Anda belum menentukan tahun");
		
	}
	else{

		$ta = preg_replace('#[^0-9]#', '',$_POST['ta']);

		$sqlPagu = "SELECT SUM(jumlah_anggaran) as ja FROM kegiatan WHERE ta=$ta";
		$resPagu = $db->query($sqlPagu);
		$fetchPagu = $resPagu->fetch_object();
		$nilaiPagu = round($fetchPagu->ja/1000000000000, 3);

		$countUrusan = $db->query("SELECT urusan_id FROM kegiatan where ta=$ta");
		$jmlhUrusan = $countUrusan->num_rows;

		$countProgram = $db->query("SELECT program_id FROM kegiatan where ta=$ta ");
		$jmlhProgram = $countProgram->num_rows;

		$countKegiatan = $db->query("SELECT  nama_kegiatan FROM kegiatan where ta=$ta");
		$jmlhKegiatan = $countKegiatan->num_rows;


		function getNamaInstansi($id_instansi){

			$db = new mysqli('localhost', 'root', '', 'simbangdabaru');
			$sql = "SELECT nama_instansi FROM instansi WHERE id_instansi=$id_instansi LIMIT 1";
			$res = $db->query($sql);
			$fetchIntansi = $res->fetch_object();
			$nama_instansi = $fetchIntansi->nama_instansi;
			return $nama_instansi;
		}
		
		


		$table = '<div class="col-md-3 alert alert-info">
						<h3> Pagu : '.$nilaiPagu.' T</h3>
					</div>
					<div class="col-md-3 alert alert-info">
						<h3> Jumlah Urusan : '.$jmlhUrusan.'</h3>
					</div>
					<div class="col-md-3 alert alert-info">
						<h3> Jumlah Program  : '.$jmlhProgram.'</h3>
					</div>
					<div class="col-md-3 alert alert-info">
						<h3> Jumlah Kegiatan : '.$jmlhKegiatan.'</h3>
					</div>

					<table class="table table-striped" id="tblResultSinkron">

						<thead>
							
							<tr>
								<th>No Rekening</th>
								<th style="width:25%">Kegiatan</th>
								<th style="text-align:center;">Pagu Anggaran</th>
								<th style="text-align:center; width:25%">SKPD</th>
							</tr>
						</thead>

						<tbody>';

			$rincian = "SELECT kode_rekening,nama_kegiatan,jumlah_anggaran,id_instansi FROM kegiatan WHERE ta='$ta'";
			$resRincian = $db->query($rincian);
			while($rowRincian = $resRincian->fetch_object()){

				$table.='<tr>';
				$table.='<td>'.$rowRincian->kode_rekening.'</td>';
				$table.='<td>'.$rowRincian->nama_kegiatan.'</td>';
				$table.='<td style="text-align:center;">'.$rowRincian->jumlah_anggaran.'</td>';
				$table.='<td style="text-align:center;">'.getNamaInstansi($rowRincian->id_instansi).'</td>';
				$table.='</tr>';
			}


		$table .=		'</tbody>';

		$table.=		'</table>';


		echo $table;
	}



?>
