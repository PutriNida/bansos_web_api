<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Detail Pengajuan
    </title>
</head>

<body>
    <?php
    include '../templates/header.php';
    include '../templates/navigator.php';
    include '../global/config.php';

    if (empty($_SESSION['username'])) {
        $_SESSION['error'] = '<center>Anda harus login terlebih dahulu!</center>';
        header("Location: ./");
        die();
    } else { ?>

        <!-- page content -->
        <div class="right_col" role="main">
            <div class="row">
                <?php if (!empty($_SESSION['error'])): ?>
                    <div class="x_content bs-example-popovers">
                        <div class="alert alert-danger alert-dismissible " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <?php echo $_SESSION['error'];
                            unset($_SESSION['error']); ?>
                        </div>
                    </div>
                <?php elseif (!empty($_SESSION['message'])): ?>
                    <div class="x_content bs-example-popovers">
                        <div class="alert alert-success alert-dismissible " role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                    aria-hidden="true">×</span>
                            </button>
                            <?php echo $_SESSION['message'];
                            unset($_SESSION['message']); ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <?php
                            $row = array();
                            $query = mysqli_query($config, "SELECT * FROM pengajuan, user_akun, desa, status_pengajuan WHERE
                        pengajuan.id_akun = user_akun.id_akun AND user_akun.id_desa = desa.id_desa AND
                        pengajuan.id_status = status_pengajuan.id_status AND pengajuan.id_pengajuan = '" .
                                $_REQUEST['id'] . "'");
                            if (mysqli_num_rows($query) == 1) {
                                $row = mysqli_fetch_assoc($query);
                            } else {
                                $_SESSION['error'] = 'Data Tidak Ditemukan!';
                                header("location:../views/penyewaan.php");
                            }
                            ?>

                            <h5 class="card-title">Detail Pengajuan</h5>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-3 "> Tanggal Pengajuan</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control"
                                        value="<?php echo tgl_indo($row['tgl_pengajuan']); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-3 "> Nama</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control" value="<?= $row['nama_lengkap']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-3 "> Desa</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control" value="<?= $row['nama_desa']; ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-3 "> Nominal</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control"
                                        value="Rp <?= number_format((int) $row['nominal'], 0, ',', '.'); ?>" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-3 col-sm-3 "> Status Pengajuan</label>
                                <div class="col-md-9 col-sm-9 ">
                                    <input type="text" class="form-control" value="<?= $row['status_pengajuan']; ?>"
                                        readonly>
                                </div>
                            </div>
                            <?php if (isset($_REQUEST['kept'])): ?>
                                <h5 class="card-title">Keputusan Pengajuan</h5>
                                <form method="POST" action="../actions/act_pengajuan.php?do=keputusan">
                                    <input type="hidden" class="form-control" name="id_pengajuan"
                                        value="<?= $_REQUEST['id'] ?>">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Keputusan</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <select class="form-control" name="id_status" id="id_status" onselect="showHide()"
                                                required>
                                                <option value="">--Pilih Keputusan--</option>
                                                <option value="3">Setuju</option>
                                                <option value="4">Tidak Setuju</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Nominal Disetujui</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="number" class="form-control" placeholder="0" name="nominal_setuju">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Alasan Ditolak</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <textarea class="form-control" name="alasan"></textarea>
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group row">
                                        <div class="col-md-9 col-sm-9  offset-md-3">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            <?php elseif (isset($_REQUEST['ambil'])): ?>
                                <h5 class="card-title">Pengambilan Dana Bantuan</h5>
                                <form method="POST" action="../actions/act_pengajuan.php?do=diambil">
                                    <input type="hidden" class="form-control" name="id_pengajuan"
                                        value="<?= $_REQUEST['id'] ?>">
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Tanggal Ambil</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="date" class="form-control" name="tgl_ambil">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Diambil Oleh</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control" name="diambil_oleh">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Nominal Ambil</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="number" class="form-control" placeholder="0" name="nominal_ambil">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Pegawai Kecamatan</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control" name="diserahkan_oleh">
                                        </div>
                                    </div>
                                    <div class="ln_solid"></div>
                                    <div class="form-group row">
                                        <div class="col-md-9 col-sm-9  offset-md-3">
                                            <button type="submit" class="btn btn-success">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            <?php elseif (isset($_REQUEST['dtl'])): ?>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 "> Nominal Disetujui</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="text" class="form-control"
                                            value="Rp <?= number_format((int) $row['nominal_setuju'], 0, ',', '.'); ?>"
                                            readonly>
                                    </div>
                                </div>
                                <?php if ($row['id_ambil'] == 1): ?>
                                    <a href="../views/detail_pengajuan.php?id=<?= $row['id_pengajuan']; ?>&ambil=" type="button"
                                        class="btn btn-primary btn-sm" name="ambil"></span> Dana Bantuan Diambil</a>
                                <?php elseif ($row['id_ambil'] == 2 && $row['id_status'] == 3): ?>: ?>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Tanggal Ambil</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control"
                                                value="<?php echo tgl_indo($row['tgl_ambil']); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Diambil Oleh</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control" value="<?= $row['diambil_oleh']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Nominal Diambil</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control"
                                                value="Rp <?= number_format((int) $row['nominal_ambil'], 0, ',', '.'); ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label col-md-3 col-sm-3 "> Petugas</label>
                                        <div class="col-md-9 col-sm-9 ">
                                            <input type="text" class="form-control" value="<?= $row['diserahkan_oleh']; ?>"
                                                readonly>
                                        </div>
                                    </div>
                                <?php endif; ?>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
        <?php
    }
    include '../templates/footer.php'; ?>