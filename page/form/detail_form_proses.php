<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data email dari form
    $datas = $_POST["data"];
    $nama_db = $_POST["kode_form"];
    // Simpan data dalam bentuk array
    $data = array(
        "datas" => $datas
    );

    // Konversi data menjadi format JSON
    $jsonData = json_encode($data);

    // Tentukan nama file JSON yang ingin Anda buat
    $namaFile = "form/".$nama_db . ".json"; // Perbaiki pemisahnya (tambahkan titik)

    // Coba buka file JSON untuk penulisan
    if (file_put_contents($namaFile, $jsonData)) {
        echo "Data berhasil disimpan dalam file JSON.";
		header("location:../../index.php?page=index_form");
    } else {
        echo "Gagal menyimpan data dalam file JSON.";
    }
}
?>
