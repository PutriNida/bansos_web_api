<?php
require_once '../global/config.php';
require_once '../global/functions.php';

$config = conn($host, $username, $password, $database);

$response = array();

if (isset($_GET['do'])) {

    switch ($_GET['do']) {
        case 'desa':
            $stmt = mysqli_query($config, "SELECT * FROM desa");
            if (mysqli_num_rows($stmt) > 0) {
                $desa = array();

                while ($row = mysqli_fetch_assoc($stmt)) {
                    $temp = array(
                        "id_desa" => $row["id_desa"],
                        "nama_desa" => $row["nama_desa"]
                    );
                    array_push($desa, $temp);
                }
                $response['error'] = false;
                $response['message'] = 'OK';
                $response['desa'] = $desa;
            } else {
                //if the user not found 
                $response['error'] = false;
                $response['message'] = 'Gagal Memuat Data!';
            }
            break;


        case 'registrasi':
            if (isTheseParametersAvailable(array('id_desa', 'nama_lengkap', 'alamat', 'no_telp', 'email', 'username', 'password'))) {

                //getting the values 
                $id_desa = $_POST['id_desa'];
                $nama_lengkap = $_POST['nama_lengkap'];
                $alamat = $_POST['alamat'];
                $no_telp = $_POST['no_telp'];
                $email = $_POST['email'];
                $username = $_POST['username'];
                $password = $_POST['password'];

                $stmt = mysqli_query($config, "SELECT * FROM user_akun WHERE username = '" . $username . "' OR email = '" . $email . "'");

                //if the user already exist in the database 
                if (mysqli_num_rows($stmt) > 0) {
                    $response['error'] = true;
                    $response['message'] = 'User Akun Sudah Ada!';
                } else {

                    //if user is new creating an insert query 
                    $stmt = mysqli_query($config, "INSERT INTO user_akun (id_desa, nama_lengkap, alamat, no_telp, email, username, password, id_level) VALUES ('" . $id_desa . "', '" . $nama_lengkap . "', '" . $alamat . "', '" . $no_telp . "', '" . $email . "', '" . $username . "', '" . $password . "', 2)");

                    //if the user is successfully added to the database 
                    if ($stmt) {
                        $response['error'] = false;
                        $response['message'] = 'User Akun Berhasil Dibuat, Silahkan Login!';
                    }
                }

            } else {
                $response['error'] = true;
                $response['message'] = 'Parameter tidak sesuai, silahkan hubungi developer';
            }
            break;

        case 'login':
            if (isTheseParametersAvailable(array('username', 'password'))) {

                //getting the values 
                $username = $_POST['username'];
                $password = $_POST['password'];

                $stmt = mysqli_query($config, "SELECT * FROM user_akun WHERE username = '" . $username . "' AND password = '" . $password . "'");

                //if the user already exist in the database 
                if (mysqli_num_rows($stmt) > 0) {
                    $row = mysqli_fetch_assoc($stmt);
                    if ($row["id_desa"] == null) {
                        $response['error'] = true;
                        $response['message'] = 'Anda tidak memiliki Hak Akses untuk Aplikasi Mobile!';
                    } else {
                        $getdesa = mysqli_query($config, "SELECT * FROM desa WHERE id_desa = '" . $row['id_desa'] . "'");
                        $desa = mysqli_fetch_assoc($getdesa);
                        $user = [
                            "username" => $username,
                            "id_akun" => $row["id_akun"],
                            "id_desa" => $row["id_desa"],
                            "desa" => $desa["nama_desa"],
                            "nama_lengkap" => $row["nama_lengkap"],
                            "alamat" => $row["alamat"],
                            "no_telp" => $row["no_telp"],
                            "email" => $row["email"]
                        ];

                        $response['error'] = false;
                        $response['message'] = 'Login Berhasil!';
                        $response['user'] = $user;
                    }

                } else {
                    $response['error'] = true;
                    $response['message'] = 'Login Gagal! Username dan Password Tidak Sesuai!';
                }

            } else {
                $response['error'] = true;
                $response['message'] = 'Parameter tidak sesuai, silahkan hubungi developer!';
            }
            break;

        case 'add':
            if (isTheseParametersAvailable(array('id_akun', 'nominal', 'keperluan', 'detail'))) {
                //getting the values 
                $id_akun = $_POST['id_akun'];
                $nominal = $_POST['nominal'];
                $keperluan = $_POST['keperluan'];
                $detail = $_POST['detail'];

                //if user is new creating an insert query 
                $stmt = mysqli_query($config, "INSERT INTO pengajuan (id_akun, tgl_pengajuan, nominal, keperluan, detail, id_status) VALUES ('" . $id_akun . "', '" . date('Y-m-d') . "','" . $nominal . "', '" . $keperluan . "', '" . $detail . "', 1)");

                //if the user is successfully added to the database 
                if ($stmt) {
                    $response['error'] = false;
                    $response['message'] = 'Pengajuan Berhasil Dibuat!';
                } else {
                    $response['error'] = true;
                    $response['message'] = 'Pengajuan Gagal Dibuat!';
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'Parameter tidak sesuai, silahkan hubungi developer!';
            }
            break;

        case 'count':
            $id_akun = $_REQUEST['id_akun'];
            $countall = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "'");
            $countproses = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "' AND id_status = 2");
            $countsetuju = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "' AND id_status = 3");
            $counttolak = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "' AND id_status = 4");
            if ($countall && $countproses && $countsetuju && $counttolak) {
                $response['error'] = false;
                $response['all'] = mysqli_num_rows($countall);
                $response['proses'] = mysqli_num_rows($countproses);
                $response['setuju'] = mysqli_num_rows($countsetuju);
                $response['tolak'] = mysqli_num_rows($counttolak);
            } else {
                $response['error'] = true;
                $response['message'] = 'Data Tidak Ditemukan!';
            }
            break;

        case 'select':
            $id_akun = $_REQUEST['id_akun'];
            $id_status = $_REQUEST['id_status'];
            if ($id_status == 0) {
                $stmt = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "' ORDER BY tgl_pengajuan DESC");
            } else {
                $stmt = mysqli_query($config, "SELECT * FROM pengajuan WHERE id_akun = '" . $id_akun . "' AND id_status = '" . $id_status . "' ORDER BY tgl_pengajuan DESC");
            }

            if (mysqli_num_rows($stmt) > 0) {
                $pengajuan = array();

                while ($row = mysqli_fetch_assoc($stmt)) {
                    $temp = array(
                        "id_pengajuan" => $row["id_pengajuan"],
                        "id_akun" => $row["id_akun"],
                        "tgl_pengajuan" => $row["tgl_pengajuan"],
                        "nominal" => $row["nominal"],
                        "keperluan" => $row["keperluan"],
                        "detail" => $row["detail"],
                        "id_status" => $row["id_status"],
                        "nominal_setuju" => $row["nominal_setuju"],
                        "alasan_ditolak" => $row["alasan_ditolak"],
                        "id_ambil" => $row["id_ambil"],
                        "tgl_ambil" => $row["tgl_ambil"],
                        "diambil_oleh" => $row["diambil_oleh"],
                        "nominal_ambil" => $row["nominal_ambil"],
                        "diserahkan_oleh" => $row["diserahkan_oleh"]
                    );
                    array_push($pengajuan, $temp);
                }
                $response['error'] = false;
                $response['message'] = 'OK';
                $response['pengajuan'] = $pengajuan;
            } else {
                $response['error'] = false;
                $response['message'] = 'Data Tidak Ditemukan!';
            }
            break;

        default:
            $response['error'] = true;
            $response['message'] = 'Invalid Operation Called';
    }

} else {
    $response['error'] = true;
    $response['message'] = 'Invalid API Call';
}

echo json_encode($response);
?>