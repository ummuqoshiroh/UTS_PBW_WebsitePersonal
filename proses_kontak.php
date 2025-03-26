<?php
// Cek apakah request adalah POST
error_reporting(E_ALL);
ini_set('display_errors', 1);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitasi dan validasi input
    $nama = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
    $subjek = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $pesan_isi = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';

    // Validasi data
    $errors = [];
    if (empty($nama)) $errors[] = "Nama harus diisi";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid";
    if (empty($subjek)) $errors[] = "Subjek harus diisi";
    if (empty($pesan_isi)) $errors[] = "Pesan harus diisi";

    // Jika tidak ada error, proses pengiriman
    if (empty($errors)) {
        // Email tujuan
        $to = "ummu.qoshiroh@example.com";
        
        // Subjek email
        $email_subject = "Pesan Baru dari Formulir Kontak: $subjek";
        
        // Isi pesan email
        $email_body = "Anda menerima pesan baru dari formulir kontak:\n\n" .
                      "Nama: $nama\n" .
                      "Email: $email\n" .
                      "Subjek: $subjek\n\n" .
                      "Pesan:\n$pesan_isi";
        
        // Header email
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();

        // Kirim email
        $kirim = mail($to, $email_subject, $email_body, $headers);

        // Redirect berdasarkan status pengiriman
        if ($kirim) {
            header("Location: contact.html?status=success");
            exit();
        } else {
            header("Location: contact.html?status=error");
            exit();
        }
    } else {
        // Jika ada error, redirect dengan pesan error
        $error_message = urlencode(implode(", ", $errors));
        header("Location: contact.html?status=error&message=$error_message");
        exit();
    }
} else {
    // Jika bukan metode POST, redirect
    header("Location: contact.html");
    exit();
}
?>