<?php
error_reporting(0);
include"connect.php";

    $selectedYear = '2014';

    //$detailInstansi = '';
    $detailInstansi = '<tbody>';

    if(isset($_POST['selectedYear'])){
        $selectedYear = preg_replace('#[^0-9]#', '', $_POST['selectedYear']);
    }

    if(!isset($_POST['id_instansi'])){
        exit("Id Instansi Kosong");
    }else{

        $sumTotalSpj = array();

        $id_instansi = preg_replace('#[^0-9]#', '', $_POST['id_instansi']);

        $sqlKegiatan = "SELECT 
                    kegiatan.id_kegiatan,kegiatan.id_instansi,kegiatan.nama_kegiatan,kegiatan.jumlah_anggaran,kegiatan.kontrak_nilai,kegiatan.non_kontrak_nilai,
                    instansi.nama_instansi
                    FROM kegiatan
                    INNER JOIN instansi
                    ON kegiatan.id_instansi=instansi.id_instansi
                    WHERE kegiatan.ta =$selectedYear AND instansi.id_instansi=$id_instansi
                    ORDER BY kegiatan.id_kegiatan
                    ";
        $resultsqlKegiatan = $db->query($sqlKegiatan);
        if($resultsqlKegiatan->num_rows){
            while($rowKegiatan = $resultsqlKegiatan->fetch_object()){

                $totalKontrak = $rowKegiatan->kontrak_nilai+$rowKegiatan->non_kontrak_nilai;
                $jumlahAnggaran = $rowKegiatan->jumlah_anggaran;
                $jumlahAnggaranTampil = $jumlahAnggaran/1000000000;
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
                $totalSpjTampil = $totalSpj/1000000000;  //Dalam orde Milyard.
                $sumTotalSpj[] = $totalSpj;

                $prosentaseSpjPerkegiatan = $totalSpj/$jumlahAnggaran*100;


                $detailInstansi.='<tr>
                                    <td>'.$rowKegiatan->nama_kegiatan.'</td>
                                    <td style="text-align:right;">'.number_format($jumlahAnggaran).'</td>
                                    <td style="text-align:right;">'.number_format($totalSpj).'</td>
                                    <td style="text-align:right;">'.substr($prosentaseSpjPerkegiatan,0,4).'</td>
                                    <td style="text-align:right;">'.substr($prosentasePerKegiatan,0,4).'</td> 
                                  </tr>';
            }
        }
    }

    $detailInstansi .='</tbody>';

    echo $detailInstansi;
?>