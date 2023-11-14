<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>
        Laporan
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
                            <h5 class="card-title">Laporan Pengajua</h5>
                            <form method="POST" action="../actions/act_laporan.php" target="_blank">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 "> Status Pengajuan</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <select class="form-control" name="id_status" required>
                                            <option value="0"> Semua</option>
                                            <?php
                                            $query = mysqli_query($config, "SELECT * FROM status_pengajuan");
                                            while ($d = mysqli_fetch_assoc($query)):
                                                ?>
                                                <option value="<?= $d['id_status']; ?>">
                                                    <?= $d['status_pengajuan']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 "> Status Pengambilan</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <select class="form-control" name="id_ambil" required>
                                            <option value="0"> Semua</option>
                                            <?php
                                            $query = mysqli_query($config, "SELECT * FROM status_pengambilan");
                                            while ($d = mysqli_fetch_assoc($query)):
                                                ?>
                                                <option value="<?= $d['id_ambil']; ?>">
                                                    <?= $d['diambil']; ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 "> Dari</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="dari"
                                            required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-3 col-sm-3 "> Sampai</label>
                                    <div class="col-md-9 col-sm-9 ">
                                        <input type="date" class="form-control" placeholder="dd/mm/yyyy" name="sampai"
                                            required>
                                    </div>
                                </div>
                                <div class="ln_solid"></div>
                                <div class="form-group row">
                                    <div class="col-md-9 col-sm-9  offset-md-3">
                                        <button type="submit" class="btn btn-success">Cetak</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /page content -->
        <?php
    }
    include '../templates/footer.php'; ?>