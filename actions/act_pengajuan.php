<?php
session_start();

include '../global/config.php';
include '../global/functions.php';
$config = conn($host, $username, $password, $database);

if (isset($_REQUEST['do'])) {
    $do = $_REQUEST['do'];
    switch ($do) {
        case 'diproses':
            if (isset($_REQUEST['id'])) {
                $id_pengajuan = $_REQUEST['id'];

                $cekdata = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_pengajuan='" . $id_pengajuan . "'");

                if (mysqli_num_rows($cekdata) == 0) {
                    $_SESSION['error'] = 'Data Tidak Ditemukan!';
                    header("location:../views/pengajuan.php");
                } else {
                    $query = mysqli_query($config, "UPDATE pengajuan SET id_status = 2 WHERE id_pengajuan='" . $id_pengajuan . "'");
                    if ($query) {
                        $_SESSION['message'] = 'Perubahan Status Pengajuan Berhasil Disimpan!';
                        header("location:../views/pengajuan.php");
                    } else {
                        $_SESSION['error'] = 'Perubahan Status Pengajuan Gagal Disimpan!';
                        header("location:../views/pengajuan.php");
                    }
                }
            } else {
                $_SESSION['error'] = 'Masalah Teknis!';
                header("location:../views/pengajuan.php");
            }
            break;

        case 'keputusan':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id_pengajuan = $_POST['id_pengajuan'];
                $id_status = $_POST['id_status'];
                $nominal_setuju = $_POST['nominal_setuju'];
                $alasan = $_POST['alasan'];

                $cekdata = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_pengajuan='" . $id_pengajuan . "'");

                if (mysqli_num_rows($cekdata) == 0) {
                    $_SESSION['error'] = 'Data Tidak Ditemukan!';
                    header("location:../views/pengajuan.php");
                } else {
                    if ($id_status == 3) {
                        $query = mysqli_query($config, "UPDATE pengajuan SET id_ambil = 1, id_status = '" . $id_status . "', nominal_setuju='" . $nominal_setuju . "' WHERE id_pengajuan='" . $id_pengajuan . "'");
                    } else if ($id_status == 4) {
                        $query = mysqli_query($config, "UPDATE pengajuan SET id_status = '" . $id_status . "', nominal_setuju= 0, alasan_ditolak='" . $alasan . "' WHERE id_pengajuan='" . $id_pengajuan . "'");
                    }
                    if ($query) {
                        $_SESSION['message'] = 'Perubahan Status Pengajuan Berhasil Disimpan!';
                        header("location:../views/pengajuan.php");
                    } else {
                        $_SESSION['error'] = 'Perubahan Status Pengajuan Gagal Disimpan!';
                        header("location:../views/pengajuan.php");
                    }
                }
            } else {
                $_SESSION['error'] = 'Masalah Teknis!';
                header("location:../views/pengajuan.php");
            }
            break;

        case 'diambil':
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $id_pengajuan = $_POST['id_pengajuan'];
                $tgl_ambil = $_POST['tgl_ambil'];
                $diambil_oleh = $_POST['diambil_oleh'];
                $nominal_ambil = $_POST['nominal_ambil'];
                $diserahkan_oleh = $_POST['diserahkan_oleh'];

                $cekdata = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_pengajuan='" . $id_pengajuan . "'");

                if (mysqli_num_rows($cekdata) == 0) {
                    $_SESSION['error'] = 'Data Tidak Ditemukan!';
                    header("location:../views/pengajuan.php");
                } else {
                    $query = mysqli_query($config, "UPDATE pengajuan SET id_ambil = 2, tgl_ambil='" . $tgl_ambil . "', diambil_oleh = '" . $diambil_oleh . "', nominal_ambil='" . $nominal_ambil . "', diserahkan_oleh='" . $diserahkan_oleh . "' WHERE id_pengajuan = '" . $id_pengajuan . "'");
                    if ($query) {
                        $_SESSION['message'] = 'Data Pengambilan Berhasil Disimpan!';
                        header("location:../views/pengajuan.php");
                    } else {
                        $_SESSION['error'] = 'Data Pengambilan Gagal Disimpan!';
                        header("location:../views/pengajuan.php");
                    }
                }
            } else {
                $_SESSION['error'] = 'Masalah Teknis!';
                header("location:../views/pengajuan.php");
            }
            break;

        case 'delete':
            if (isset($_REQUEST['id'])) {
                $id = $_REQUEST['id'];
                $cekdata = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_pengajuan='" . $id . "'");

                if (mysqli_num_rows($cekdata) == 0) {
                    $_SESSION['error'] = 'Data Tidak Ditemukan!';
                    header("location:../views/pengajuan.php");
                } else {
                    $query = mysqli_query($config, "DELETE FROM pengajuan WHERE id_pengajuan='" . $id . "'");
                    if ($query) {
                        $_SESSION['message'] = 'Data Pengajuan Berhasil Dihapus!';
                        header("location:../views/pengajuan.php");
                    } else {
                        $_SESSION['error'] = 'Data Pengajuan Gagal Dihapus!';
                        header("location:../views/pengajuan.php");
                    }
                }
            } else {
                $_SESSION['error'] = 'Masalah Teknis!';
                header("location:../views/pengajuan.php");
            }
            break;
    }
} else {
    $_SESSION['error'] = 'Masalah Teknis!';
    header("location:../views/pengajuan.php");
}

?>