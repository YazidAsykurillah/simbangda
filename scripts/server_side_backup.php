<?php
//error_reporting(0);
include"connect.php";

    $selectedYear = '2014';
    if(isset($_POST['tahun'])){
        $selectedYear = preg_replace('#[^0-9]#', '', $_POST['tahun']);
    }

    $daftarInstansi = '';

    $sqlKegiatan = "SELECT 
                    kegiatan.id_kegiatan,kegiatan.id_instansi,instansi.id_instansi
                    FROM kegiatan
                    INNER JOIN instansi
                    ON kegiatan.id_instansi=instansi.id_instansi
                    WHERE kegiatan.ta = $selectedYear
                    ";
    
    $result = $db->query($sqlKegiatan);
    if($result->num_rows ==0){
        
        $daftarInstansi .= '<center><p class="alert alert-danger">Tidak ada data yang ditampilkan</p></center>';
        echo $daftarInstansi;
        exit();
        
    }
    $arrIdInstansi = array();
    
    while($row = $result->fetch_object()){

        $id_instansi = $row->id_instansi;
        $arrIdInstansi [] = $id_instansi;
    }
    
    foreach(array_unique($arrIdInstansi)as $id){

        $sqlInstansi = "SELECT id_instansi,nama_instansi FROM instansi WHERE id_instansi=$id LIMIT 1";
        $resInstansi = $db->query($sqlInstansi) or die($db->error);
        
        $rowInstansi = $resInstansi->fetch_object();
        $idInstansi = $rowInstansi->id_instansi;
        $namaInstansi = $rowInstansi->nama_instansi;


        $sqlPagu = "SELECT SUM(jumlah_anggaran) as ja FROM kegiatan WHERE id_instansi=$id AND ta=$selectedYear";
        $resPagu = $db->query($sqlPagu);
        $rowPagu = $resPagu->fetch_object();
        $pagu = $rowPagu->ja/1000000000;    //Dalam orde milyard.


        /*
        //Asykur's NOTE   
            // <a href> dengan id ="detailViewer" merupakan anchor untuk mengambil data pertama kali ketika di klik.
            // <a href> dengan id "substituent" merupakan anchor untuk menggantikan anchor dengan id "detailViewer".
            // supaya tidak terjadi duplication pada saat anchor dengan id="detailViewer" di klik.
        //END Asykur note
        */

        $daftarInstansi.='<ul>
                            <li>

                                <a onClick="getDetail('.$idInstansi.')" id="detailViewer_'.$idInstansi.'">'.$namaInstansi.'
                                    (Pagu : '.round($pagu,2).' M )
                                </a>
                                <a id="substituent_'.$idInstansi.'" style="display:none;">'.$namaInstansi.'
                                    (Pagu : '.round($pagu,2).' M )
                                </a>
                                <ul>
                                    <table class="table table-striped table-responsive" id="tblInstansi_'.$idInstansi.'">
                                        <thead>
                                            <tr>
                                                <th style="width:50%">Kegiatan</th>
                                                <th style="text-align:center;">Pagu (M)</th>
                                                <th style="text-align:center;">SPJ (M)</th>
                                                <th style="text-align:center;">% Keuangan</th>
                                                <th style="text-align:center;">% Fisik</th>
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </ul>
                            </li>
                        </ul>';
  

    }

    echo $daftarInstansi;


    ?>