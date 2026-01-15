<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data yang dikirimkan melalui form
    $dataHasil = $_POST;
    $nama_form = $_POST['nama_form'];

    // Tentukan direktori penyimpanan gambar
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/smart_pro/uploads/";

    // Pastikan direktori upload ada, jika tidak, buat baru
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
	// Generate nama gambar
	date_default_timezone_set('Asia/Jakarta');
	$tanggal 		= date("d");
	$tahun 			= date("Y");
	$jam 			= date("His");
	
    // Proses upload gambar
    foreach ($_FILES as $key => $file) {
        if ($file['error'] == UPLOAD_ERR_OK) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid($tanggal.$tahun.$jam.'img_') . "." . $ext; // Buat nama unik untuk gambar
            $filePath = $uploadDir . $newFileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                // Simpan path relatif ke dalam data hasil
                $dataHasil[$key] = "/smart_pro/uploads/" . $newFileName;
            } else {
                echo "Gagal mengunggah gambar!";
                exit;
            }
        }
    }

    // Tentukan nama file JSON
    $nama_file = $_SERVER['DOCUMENT_ROOT'] . "/smart_pro/page/form/form/isi-" . $nama_form . ".json";

    // Membuat UUID baru
    $uuid = uniqid('', true); // Menggunakan uniqid untuk UUID
    $dataHasil['id'] = $uuid;

    // Inisialisasi array untuk data yang akan disimpan
    $dataBaru = array();

    // Menggunakan mekanisme file locking untuk mencegah race condition
    $fileHandle = fopen($nama_file, 'c+'); // Open file for reading and writing, create if not exists
    if ($fileHandle) {
        // Lock file untuk memastikan tidak ada proses lain yang mengakses file ini secara bersamaan
        if (flock($fileHandle, LOCK_EX)) {
            // Jika file sudah ada, baca data yang sudah ada
            $fileSize = filesize($nama_file);
            if ($fileSize > 0) {
                $dataLama = fread($fileHandle, $fileSize);
                $dataBaru = json_decode($dataLama, true) ?: array();
            }

            // Tambahkan data baru ke dalam array
            $dataBaru[] = $dataHasil;

            // Konversi data ke format JSON
            $jsonData = json_encode($dataBaru, JSON_PRETTY_PRINT);

            // Truncate file sebelum menulis data baru
            ftruncate($fileHandle, 0);
            rewind($fileHandle);

            // Tulis data ke file
            if (fwrite($fileHandle, $jsonData)) {
                echo "Data berhasil disimpan dalam $nama_file";
                header("location:../../index.php?page=form&nama_form=$nama_form");
                exit;
            } else {
                echo "Gagal menyimpan data!";
            }

            // Lepas lock file
            flock($fileHandle, LOCK_UN);
        } else {
            echo "Gagal mengunci file untuk mencegah konflik!";
        }

        // Tutup file
        fclose($fileHandle);
    } else {
        echo "Gagal membuka file!";
    }
} else {
    // Jika halaman ini diakses tanpa melalui form POST, tampilkan pesan kesalahan
    echo "Akses tidak valid!";
}
?>

