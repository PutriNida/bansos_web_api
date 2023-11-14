<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Daftar Pengajuan
    </title>
</head>

<body>
    <?php
    include '../global/config.php';
    include '../templates/header.php';
    include '../templates/navigator.php';

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
                        <h5 class="card-title">Daftar Pengajuan</h5>
                        <table id="datatable" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Lengkap (Nama Desa)</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nominal</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $no = 1;
                                    $query = mysqli_query($config, "SELECT * FROM pengajuan, user_akun, desa, status_pengajuan WHERE pengajuan.id_akun = user_akun.id_akun AND user_akun.id_desa = desa.id_desa AND pengajuan.id_status = status_pengajuan.id_status");

                                    if (mysqli_num_rows($query) > 0):
                                        while ($row = mysqli_fetch_array($query)):
                                            ?>
                                <tr>
                                    <td>
                                        <?= $no++; ?>
                                    </td>
                                    <td>
                                        <?= $row['nama_lengkap']; ?> (
                                        <?= $row['nama_desa']; ?>)
                                    </td>
                                    <td>
                                        <?= tgl_indo($row['tgl_pengajuan']); ?>
                                    </td>
                                    <td>
                                        <?= number_format((int) $row['nominal'], 0, ',', '.'); ?>
                                    </td>
                                    <td>
                                        <?php if (empty($row['id_ambil'])): ?>
                                        <?= $row['status_pengajuan']; ?>
                                        <?php elseif ($row['id_ambil'] == 1): ?>
                                        <a href="../views/detail_pengajuan.php?id=<?= $row['id_pengajuan']; ?>&ambil="
                                            type="button" class="btn btn-warning btn-sm"
                                            name="proses"></span>Pengambilan Dana</a>
                                        <?php else: ?>
                                        Sudah Diambil /
                                        <?php echo $row['diambil_oleh'] . ' / ' . tgl_indo($row['tgl_ambil']) . ' / ' . number_format((int) $row['nominal_ambil'], 0, ',', '.') . ' / ' . $row['diserahkan_oleh']; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($row['id_status'] == 1) { ?>
                                        <a href="../actions/act_pengajuan.php?do=diproses&id=<?= $row['id_pengajuan']; ?>"
                                            type="button" class="btn btn-warning btn-sm" name="proses"></span>Proses
                                            Pengajuan</a>
                                        <?php }
                                                    if ($row['id_status'] == 2) { ?>
                                        <a href="../views/detail_pengajuan.php?id=<?= $row['id_pengajuan']; ?>&kept="
                                            type="button" class="btn btn-warning btn-sm"
                                            name="proses"></span>Keputusan</a>
                                        <?php } ?>
                                        <a href="../views/detail_pengajuan.php?id=<?= $row['id_pengajuan']; ?>&dtl="
                                            type="button" class="btn btn-primary btn-sm" name="edit"></span>Detail</a>
                                        <a href="../actions/act_pengajuan.php?do=delete&id=<?= $row['id_pengajuan']; ?>"
                                            type="button" class="btn btn-danger btn-sm" name="hapus"
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus data ini ?')"></span>Hapus</a>
                                    </td>
                                </tr>
                                <?php endwhile; endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /page content -->
    <?php
    }
    include '../templates/footer.php'; ?>