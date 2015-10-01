<?php

use \Jacwright\RestServer\RestException;

class TestController
{

    /**
    * Server 
    *
    * @url GET /tugas2/server/$id
    */
    public function getServer($id=null){
        if($id . ".jpg" == "meow.jpg"){
            $encode_name = base64_encode($id . ".jpg");
            $lokasi = '/img/meow.jpg';
            $size = "85.7KB";
            $user = array("isi_berkas" => $encode_name, "lokasi_berkas" => $lokasi, "ukuran_berkas" => $size);
        } else {
            $user = array("status" => "data not found");
        }
        return $user;
    }

    /**
    * Client 
    *
    * @url GET /tugas2/client/$id
    */
    public function getClient($id=null){
        if($id . ".jpg" == "meow.jpg"){
            $encode_name = base64_encode($id . ".jpg");
            $lokasi = '/img/meow.jpg';
            $size = "85.7KB";
            echo '<html>';
            echo '<body>';
            echo "<img src='img\meow.jpg'/>";
            echo '<b><h3> Lokasi Pada Server : '. $lokasi . '<h3></b>';
            echo '<b><h3> Ukuran : '. $size . '<h3></b>';
            echo '</body>';
            echo '</html>';
        }
        else {
            echo '<html>';
            echo '<body>';
            echo '<b><h2>Data not found<h2></b>';
            echo '</body>';
            echo '</html>';
        }
    }
 }



