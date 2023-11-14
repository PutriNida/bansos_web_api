<?php
session_start();

include '../global/config.php';
include '../global/functions.php';
$config = conn($host, $username, $password, $database);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // menangkap data yang dikirim dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username)) {
        echo "kosong";
    } else {
        // menyeleksi data user dengan username dan password yang sesuai
        $result = mysqli_query($config, "SELECT * FROM user_akun where username='" . $username . "' and password='" . $password . "'");

        $cek = mysqli_num_rows($result);

        if ($cek > 0) {
            // echo "berhasil";
            $data = mysqli_fetch_assoc($result);
            //menyimpan session user, nama, status dan id login
            $_SESSION['username'] = $username;
            $_SESSION['id_akun'] = $data['id_akun'];

            header("location:../views/pengajuan.php");
        } else {
            // echo $cek;
            $_SESSION['error'] = 'Login GAGAL!';
            header("location:../index.php");
        }
    }
} else {
    echo "POST not found";
    $_SESSION['error'] = 'Login GAGAL!';
    header("location:../index.php");
}
?>