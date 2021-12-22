<?php

require_once("koneksi.php");
session_start();
//error_reporting(0);
if(isset($_SESSION['username'])){
    $nama = $_SESSION['username'];
    $sqlSelect = "SELECT * FROM profil WHERE nama= ?";
	$row = $db->prepare($sqlSelect);
	$row->execute(array($nama));
    $hasil = $row->fetch();
    $_SESSION['id']=$hasil['ID'];
    $nama = $hasil['nama'];
    $tgl_lahir = $hasil['tgl_lahir'];
    $alamat = $hasil['alamat'];
    $jenis_kelamin = $hasil['jenis_kelamin'];
    $no_telepon = $hasil['no_telepon'];
    $pekerjaan = $hasil['pekerjaan'];
}
if(isset($_POST['hapus'])){
    $nama = $_SESSION['username'];
	$sql = "DELETE FROM profil WHERE nama= ?";
	$row = $db->prepare($sql);
	$row->execute(array($nama));
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    $nama = "";
    $tgl_lahir = "";
    $alamat = "";
    $jenis_kelamin = "";
    $no_telepon = "";
    $pekerjaan = "";
}
if(isset($_POST['register']) and !isset($_SESSION['username'])){

    // filter data yang diinputkan
    $nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $jenis_kelamin = filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING);
    $no_telepon = filter_input(INPUT_POST, 'no_telepon', FILTER_SANITIZE_STRING);
    $pekerjaan = filter_input(INPUT_POST, 'pekerjaan', FILTER_SANITIZE_STRING);

    // menyiapkan query
    $sql = "INSERT INTO profil (nama, tgl_lahir, alamat, jenis_kelamin, no_telepon, pekerjaan) 
            VALUES (:nama, :tgl_lahir, :alamat, :jenis_kelamin, :no_telepon, :pekerjaan)";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":nama" => $nama,
        ":tgl_lahir" => $tgl_lahir,
        ":alamat" => $alamat,
        ":jenis_kelamin" => $jenis_kelamin,
        ":no_telepon" => $no_telepon,
        ":pekerjaan" => $pekerjaan
    );

    // eksekusi query untuk menyimpan ke database
    $simpan = $stmt->execute($params);
    $_SESSION['username']=$nama;
	$sqlSelect = "SELECT * FROM profil WHERE nama= ?";
	$row = $db->prepare($sqlSelect);
	$row->execute(array($nama));
    $hasil = $row->fetch();
    $_SESSION['id']=$hasil['ID'];
}

if(isset($_POST['ubah']) and isset($_SESSION['username'])){

    // filter data yang diinputkan
    $id = $_SESSION['id'];
    $nama = filter_input(INPUT_POST, 'nama', FILTER_SANITIZE_STRING);
    $tgl_lahir = $_POST['tgl_lahir'];
    $alamat = filter_input(INPUT_POST, 'alamat', FILTER_SANITIZE_STRING);
    $jenis_kelamin = filter_input(INPUT_POST, 'jenis_kelamin', FILTER_SANITIZE_STRING);
    $no_telepon = filter_input(INPUT_POST, 'no_telepon', FILTER_SANITIZE_STRING);
    $pekerjaan = filter_input(INPUT_POST, 'pekerjaan', FILTER_SANITIZE_STRING);

    // menyiapkan query
    $sql = "UPDATE profil SET nama= :nama,tgl_lahir= :tgl_lahir, alamat=:alamat,jenis_kelamin=:jenis_kelamin,
            no_telepon=:no_telepon,pekerjaan=:pekerjaan 
            WHERE id=:id";
    $stmt = $db->prepare($sql);

    // bind parameter ke query
    $params = array(
        ":id" => $id,
        ":nama" => $nama,
        ":tgl_lahir" => $tgl_lahir,
        ":alamat" => $alamat,
        ":jenis_kelamin" => $jenis_kelamin,
        ":no_telepon" => $no_telepon,
        ":pekerjaan" => $pekerjaan
    );

    // eksekusi query untuk mengubah data di database
    $ubah = $stmt->execute($params);
    $_SESSION['username']=$nama;
    $sqlSelect = "SELECT * FROM profil WHERE nama= ?";
	$row = $db->prepare($sqlSelect);
	$row->execute(array($nama));
    $hasil = $row->fetch();
    $_SESSION['id']=$hasil['ID'];
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About</title>
    <link rel="stylesheet" href="profil01.css">
</head>
<body>
    <div class="utama">
        <header>
            <h1>UKM BAHAGIA</h1>
            <ul>
                <li><a href="#">HOME</a></li>
                <li><a href="#">LOGOUT</a></li>
            </ul>
        </header>
        <div class="isi">
            <h1> Deskripsi <span>Profil</span></h1>
        </div>
        
        <div class="info">
        <form method='POST' action=''>
            <table  align="center">    
            <tr>
                <td>nama</td>
                <td>:</td>
                <td>
                <input type="text" name="nama" <?php echo "value='".$nama."'" ?>>
                </td>
            </tr>

            <tr>
                <td>tanggal lahir</td>
                <td>:</td>
                <td>
                <input type="date" name="tgl_lahir" <?php echo "value='".$tgl_lahir."'" ?>>
                </td>
            </tr>

            <tr>
                <td>alamat</td>
                <td>:</td>
                <td> 
                <Textarea name="alamat" ><?php echo $alamat ?></textarea>
                </td>
            </tr>
      
            <tr>
                <td>jenis kelamin </td>
                <td>:</td>
                <td>
                    <input type="radio" name="jenis_kelamin" value="Laki-laki" <?php if ($jenis_kelamin=="Laki-laki") {  echo "checked";} ?>>Laki-laki
                    <input type="radio" name="jenis_kelamin" value="Perempuan" <?php if ($jenis_kelamin=="Perempuan") {  echo "checked";} ?>>Perempuan
                </td>
            </tr>
        
            <tr>
                <td>nomor telepon</td>
                <td>:</td>
                <td>
                <input type="text" name="no_telepon" <?php echo "value='".$no_telepon."'" ?>>
                </td>
            </tr>

            <tr>
                <td>pekerjaan</td>
                <td>:</td>
                <td>
                <input type="text" name="pekerjaan" <?php echo "value='".$pekerjaan."'" ?>>
                </td>
            </tr>
            
            <tr>
                <td>
                    <input type="submit" class="tombol_control" name="ubah" value="Ubah"/>
                </td>
                <td>
                    <input type="submit" class="tombol_control" name="register" value="Simpan"/>
                </td>
                <td>
                    <input type="submit" class="tombol_control" name="hapus" value="Hapus"/>
                </td>
            </tr>
            </table>
        </form>
        </div>

        <div class="images">
            <img src="14.png" class="gambar1">
            <img src="14.png" class="gambar2">   
        </div>

    </div>
</body>
</html>
            