<?php
// Validasi ID dan ID Form
if (!empty($_POST['id']) && !empty($_POST['id_form'])) {
    $id = $_POST['id'];
    $id_form = $_POST['id_form'];

    $jsonFile = $_SERVER['DOCUMENT_ROOT'] . "/pms/page/form/form/isi-" . $id_form . ".json";

    // Cek apakah file JSON ada
    if (!file_exists($jsonFile)) {
        echo "File JSON tidak ditemukan.";
        exit;
    }

    // Baca konten file JSON
    $jsonContent = file_get_contents($jsonFile);

    // Validasi apakah konten file JSON berhasil dibaca
    if ($jsonContent === false) {
        echo "Gagal membaca konten file JSON.";
        exit;
    }

    // Ubah data JSON menjadi array PHP
    $data = json_decode($jsonContent, true);

    // Validasi apakah data JSON berhasil di-decode
    if ($data === null) {
        echo "Gagal mendekode data JSON.";
        exit;
    }

    // Inisialisasi variabel untuk menandai apakah data ditemukan
    $dataFound = false;

    // Cari indeks elemen yang akan dihapus berdasarkan ID
    foreach ($data as $index => $item) {
        if ($item['id'] == $id) {
            $dataFound = true;
            unset($data[$index]); // Hapus item dari array
            break; // Berhenti setelah menemukan satu data
        }
    }
	/**
    // Cek apakah ada data yang ditemukan dan dihapus
    if ($dataFound) {
        // Konversi kembali array ke format JSON
        $newJsonContent = json_encode(array_values($data), JSON_PRETTY_PRINT);

        // Simpan kembali JSON yang telah diubah ke dalam file
        if (file_put_contents($jsonFile, $newJsonContent) !== false) {
            echo "Data dengan ID $id telah dihapus.";
        } else {
            echo "Gagal menyimpan perubahan.";
        }
    } else {
        echo "Data dengan ID $id tidak ditemukan.";
    }
	**/
} else {

    echo "ID atau ID Form kosong.";
}
?>
