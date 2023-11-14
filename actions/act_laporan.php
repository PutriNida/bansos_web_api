<?php
session_start();

include '../global/config.php';
include '../global/functions.php';
$config = conn($host, $username, $password, $database);

require('../assets/fpdf185/fpdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mysql = '';
    $id_status = $_POST['id_status'];
    $id_ambil = $_POST['id_ambil'];
    $dari = $_POST['dari'];
    $sampai = $_POST['sampai'];

    // echo $id_status . "  " . $id_ambil . "  " . $dari . "  " . $sampai;

    if ($id_status == 0 && $id_ambil == 0) {
        $mysql = "SELECT * FROM pengajuan WHERE tgl_pengajuan BETWEEN '" . $dari . "' AND '" . $sampai . "'";
    } else if ($id_status == 0 && $id_ambil > 0) {
        $mysql = "SELECT * FROM pengajuan WHERE id_ambil = '" . $id_ambil . "'  AND tgl_pengajuan BETWEEN '" . $dari . "' AND '" . $sampai . "'";
    } else if ($id_status > 0 && $id_ambil == 0) {
        $mysql = "SELECT * FROM pengajuan WHERE id_status = '" . $id_status . "'  AND tgl_pengajuan BETWEEN '" . $dari . "' AND '" . $sampai . "'";
    } else if ($id_status > 0 && $id_ambil > 0) {
        $mysql = "SELECT * FROM pengajuan WHERE id_ambil = '" . $id_ambil . "' AND id_status = '" . $id_status . "'  AND tgl_pengajuan BETWEEN '" . $dari . "' AND '" . $sampai . "'";
    }

    $query = mysqli_query($config, $mysql);
    if (mysqli_num_rows($query) > 0) {
        $pdf = new FPDF('l', 'mm', 'A4');
        // membuat halaman baru
        $pdf->AddPage();

        // menyetel font yang digunakan, font yang digunakan adalah arial, bold dengan ukuran 16
        $pdf->SetFont('Arial', 'B', 16);
        //kop surat
        $pdf->Image('../assets/images/logo_kabupaten_rokan_hulu.png', 20, 10, 20, 25);

        $pdf->SetX(20);
        $pdf->SetFont('Times', 'B', 17);
        $pdf->Cell(280, 3, 'PEMERINTAH KABUPATEN ROKAN HULU ', 0, 1, 'C');
        $pdf->Ln(3);
        $pdf->SetX(20);
        $pdf->SetFont('Times', 'B', 20);
        $pdf->Cell(280, 10, 'KECAMATAN KEPENUHAN', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->SetX(20);
        $pdf->Cell(280, 5, 'Jl. Syekh Abdul Wahab Rokan, Kepenuhan Tengah, Kec. Kepenuhan, Kabupaten Rokan Hulu, Riau 28558', 0, 1, 'C');
        $pdf->Ln(4);

        //garis
        $pdf->SetLineWidth(1);
        $pdf->Line(10, 36, 287, 36);
        $pdf->SetLineWidth(0);
        $pdf->Line(10, 37, 287, 37);

        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(10, 10, 'No.', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Tanggal Pengajuan', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Nama Lengkap', 1, 0, 'C');
        $pdf->Cell(40, 10, 'Desa', 1, 0, 'C');
        $pdf->Cell(35, 10, 'Nominal', 1, 0, 'C');
        $pdf->Cell(30, 10, 'Status', 1, 0, 'C');
        $pdf->Cell(70, 10, 'Keterangan', 1, 1, 'C');

        $pdf->SetFont('Arial', '', 10);

        $no = 1;

        while ($row = mysqli_fetch_array($query)) {
            $getuser = mysqli_query($config, "SELECT * FROM user_akun, desa WHERE desa.id_desa = user_akun.id_desa AND user_akun.id_akun = '" . $row['id_akun'] . "'");
            $user = mysqli_fetch_assoc($getuser);

            $getstatus = mysqli_query($config, "SELECT * FROM status_pengajuan WHERE id_status = '" . $row['id_status'] . "'");
            $datastatus = mysqli_fetch_assoc($getstatus);

            $getambil = mysqli_query($config, "SELECT * FROM status_pengambilan WHERE id_ambil = '" . $row['id_ambil'] . "'");
            $dataambil = mysqli_fetch_assoc($getambil);

            $line = 1;
            //tgl pengajuan
            $ln_pengajuan = 0;
            $tgl_pengajuan = tgl_indo($row['tgl_pengajuan']);

            if ($pdf->GetStringWidth($tgl_pengajuan) > 35) {

                $textLength = strlen($tgl_pengajuan); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (35 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($tgl_pengajuan, $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_pengajuan = count($textArray);
                if ($ln_pengajuan > $line) {
                    $line = $ln_pengajuan;
                }
            }

            //nama lengkap
            $ln_nama = 0;

            if ($pdf->GetStringWidth($user['nama_lengkap']) > 40) {

                $textLength = strlen($user['nama_lengkap']); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (40 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($user['nama_lengkap'], $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_nama = count($textArray);
                if ($ln_nama > $line) {
                    $line = $ln_nama;
                }
            }

            //desa
            $ln_desa = 0;

            if ($pdf->GetStringWidth($user['nama_desa']) > 40) {

                $textLength = strlen($user['nama_desa']); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (40 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($user['nama_desa'], $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_desa = count($textArray);
                if ($ln_desa > $line) {
                    $line = $ln_desa;
                }
            }

            //nominal
            $ln_nominal = 0;
            $nominal = 'Rp ' . number_format((int) $row['nominal'], 0, ',', '.');

            if ($pdf->GetStringWidth($nominal) > 35) {

                $textLength = strlen($nominal); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (35 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($nominal, $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_nominal = count($textArray);
                if ($ln_nominal > $line) {
                    $line = $ln_nominal;
                }
            }

            //status
            $ln_status = 0;

            if ($pdf->GetStringWidth($datastatus['status_pengajuan']) > 30) {

                $textLength = strlen($datastatus['status_pengajuan']); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (30 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($datastatus['status_pengajuan'], $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_status = count($textArray);
                if ($ln_status > $line) {
                    $line = $ln_status;
                }
            }

            //keterangan
            $ln_keterangan = 0;
            $keterangan = '-';
            if ($row['id_status'] == '3') {
                $keterangan = 'Rp ' . number_format((int) $row['nominal_setuju'], 0, ',', '.');
                if ($row['id_ambil'] == '2') {
                    $keterangan = $keterangan . " - " . $dataambil['diambil'] . " / Tanggal Ambil : " . tgl_indo($row["tgl_ambil"]) .
                        " / Diambil Oleh : " . $row["diambil_oleh"] . " / Nominal : Rp " . number_format((int) $row['nominal_ambil'], 0, ',', '.') .
                        " / Petugas : " . $row['diserahkan_oleh'];
                }
            } else {
                $keterangan = $row['alasan_ditolak'];
            }


            if ($pdf->GetStringWidth($keterangan) > 70) {

                $textLength = strlen($keterangan); //total panjang teks
                $errMargin = 5; //margin kesalahan lebar sel, untuk jaga-jaga
                $startChar = 0; //posisi awal karakter untuk setiap baris
                $maxChar = 0; //karakter maksimum dalam satu baris, yang akan ditambahkan nanti
                $textArray = array(); //untuk menampung data untuk setiap baris
                $tmpString = ""; //untuk menampung teks untuk setiap baris (sementara)

                while ($startChar < $textLength) { //perulangan sampai akhir teks
                    //perulangan sampai karakter maksimum tercapai
                    while (
                        $pdf->GetStringWidth($tmpString) < (70 - $errMargin) &&
                        ($startChar + $maxChar) < $textLength
                    ) {
                        $maxChar++;
                        $tmpString = substr($keterangan, $startChar, $maxChar);
                    }
                    //pindahkan ke baris berikutnya
                    $startChar = $startChar + $maxChar;
                    //kemudian tambahkan ke dalam array sehingga kita tahu berapa banyak baris yang dibutuhkan
                    array_push($textArray, $tmpString);
                    //reset variabel penampung
                    $maxChar = 0;
                    $tmpString = '';

                }
                //dapatkan jumlah baris
                $ln_keterangan = count($textArray);
                if ($ln_keterangan > $line) {
                    $line = $ln_keterangan;
                }
            }

            $cellHeight = 6; //tinggi sel satu baris normal
            $cellHeightLast = $line * $cellHeight;


            $pdf->Cell(10, $cellHeightLast, $no++, 1, 0, 'C');
            if ($pdf->GetStringWidth($tgl_pengajuan) > 35) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(35, ($cellHeightLast / $ln_pengajuan), $tgl_pengajuan, 1, 'L');
                $pdf->SetXY($xPos + 35, $yPos);
            } else {
                $pdf->Cell(35, $cellHeightLast, $tgl_pengajuan, 1, 0, 'L');
            }
            if ($pdf->GetStringWidth($user['nama_lengkap']) > 40) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(40, ($cellHeightLast / $ln_nama), $user['nama_lengkap'], 1, 'L');
                $pdf->SetXY($xPos + 40, $yPos);
            } else {
                $pdf->Cell(40, $cellHeightLast, $user['nama_lengkap'], 1, 0, 'L');
            }
            if ($pdf->GetStringWidth($user['nama_desa']) > 40) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(40, ($cellHeightLast / $ln_nama), $user['nama_desa'], 1, 'L');
                $pdf->SetXY($xPos + 40, $yPos);
            } else {
                $pdf->Cell(40, $cellHeightLast, $user['nama_desa'], 1, 0, 'L');
            }
            if ($pdf->GetStringWidth($nominal) > 35) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(35, ($cellHeightLast / $ln_nominal), $nominal, 1, 'L');
                $pdf->SetXY($xPos + 35, $yPos);
            } else {
                $pdf->Cell(35, $cellHeightLast, $nominal, 1, 0, 'L');
            }
            if ($pdf->GetStringWidth($datastatus['status_pengajuan']) > 30) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(30, ($cellHeightLast / $ln_status), $datastatus['status_pengajuan'], 1, 'L');
                $pdf->SetXY($xPos + 30, $yPos);
            } else {
                $pdf->Cell(30, $cellHeightLast, $datastatus['status_pengajuan'], 1, 0, 'L');
            }
            if ($pdf->GetStringWidth($keterangan) > 70) {
                $xPos = $pdf->GetX();
                $yPos = $pdf->GetY();
                $pdf->MultiCell(70, ($cellHeightLast / $ln_keterangan), $keterangan, 1, 'L');
                $pdf->SetXY($xPos + 70, $yPos);
            } else {
                $pdf->Cell(70, $cellHeightLast, $keterangan, 1, 0, 'L');
            }
            $pdf->Cell(1, $cellHeightLast, '', 0, 1, 'L');

        }
        $pdf->Output();
    } else {
        $_SESSION['error'] = 'Data Tidak Ditemukan!';
        header("location:../views/cetak_laporan.php");
    }
} else {
    $_SESSION['error'] = 'Masalah Teknis!';
    header("location:../views/akun.php");
}

?>