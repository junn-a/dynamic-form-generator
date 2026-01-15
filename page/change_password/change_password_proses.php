<?php
// Set header untuk mengirim respons JSON
header('Content-Type: application/json');

// Cek apakah request datang dari metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data password baru dan password konfirmasi dari formulir
    $newPassword = $_POST['new_password'];
    $newPasswordConfirm = $_POST['new_password_confirm'];
    
    // Lakukan validasi sederhana
    if ($newPassword !== $newPasswordConfirm) {
        // Jika password tidak cocok, kirim respons error
        $response = array(
            'status' => 'error',
            'message' => 'Password confirmation does not match!'
        );
    } else {
        // Jika password cocok, kirim respons sukses
        // Di sini Anda bisa melakukan operasi penyimpanan password baru ke database atau proses lainnya
        // Contoh sederhana hanya mengirimkan respons sukses
        $response = array(
            'status' => 'success',
            'message' => 'Password has been changed successfully!'
        );
    }

    // Mengirimkan respons dalam format JSON
    echo json_encode($response);
} else {
    // Jika request bukan dari metode POST, kirim respons error
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method!'
    );

    // Mengirimkan respons dalam format JSON
    echo json_encode($response);
}
?>
