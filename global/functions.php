<?php
date_default_timezone_set("Asia/Jakarta");

/**
 * FUngsi koneksi database.
 */

function conn($host, $username, $password, $database)
{
    $conn = mysqli_connect($host, $username, $password, $database);

    // Menampilkan pesan error jika database tidak terhubung
    return ($conn) ? $conn : "Koneksi database gagal: " . mysqli_connect_error();
}

/**
 * Fungsi untuk mengkonversi format tanggal menjadi format Indonesia.
 */
function tgl_indo($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
}

/**
 * Fungsi untuk mengkonversi format bulan angka menjadi nama bulan.
 */
function month($kode)
{
    $month = '';
    switch ($kode) {
        case '01':
            $month = 'Januari';
            break;
        case '02':
            $month = 'Februari';
            break;
        case '03':
            $month = 'Maret';
            break;
        case '04':
            $month = 'April';
            break;
        case '05':
            $month = 'Mei';
            break;
        case '06':
            $month = 'Juni';
            break;
        case '07':
            $month = 'Juli';
            break;
        case '08':
            $month = 'Agustus';
            break;
        case '09':
            $month = 'September';
            break;
        case '10':
            $month = 'Oktober';
            break;
        case '11':
            $month = 'November';
            break;
        case '12':
            $month = 'Desember';
            break;
    }
    return $month;
}

function lamasewa($tgldari, $tglsampai)
{
    $toyear = date("Y", strtotime($tglsampai));
    $tomonth = date("m", strtotime($tglsampai));
    $today = date("d", strtotime($tglsampai));
    $fromyear = date("Y", strtotime($tgldari));
    $frommonth = date("m", strtotime($tgldari));
    $fromday = date("d", strtotime($tgldari));
    $gapyear = $toyear - $fromyear;
    $gapmonth = $tomonth - $frommonth;
    $gapday = $today - $fromday;

    if ($gapmonth > 0 && $gapyear > 0) {
        return $gapday . ' hr, ' . $gapmonth . ' bln, ' . $gapyear . ' th';
    } elseif ($gapmonth > 0 && $gapyear == 0) {
        return $gapday . ' hr, ' . $gapmonth . ' bln';
    } elseif ($gapmonth == 0 && $gapyear > 0) {
        return $gapday . ' hr, ' . $gapyear . ' th';
    } else {
        return $gapday . ' hr';
    }

}

function isTheseParametersAvailable($params)
{
    //traversing through all the parameters 
    foreach ($params as $param) {
        //if the paramter is not available
        if (!isset($_POST[$param])) {
            //return false 
            return false;
        }
    }
    //return true if every param is available 
    return true;
}
?>