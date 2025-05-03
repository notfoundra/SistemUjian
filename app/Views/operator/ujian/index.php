<?= $this->extend($role . '/layout') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <?php if (session()->getFlashdata('success')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '<?= session()->getFlashdata('success') ?>',
                });
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')) : ?>
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '<?= session()->getFlashdata('error') ?>',
                });
            });
        </script>
    <?php endif; ?>

    <div class="row my-4">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">SMPN 1 Karangkancana</p>
                                <div class="d-flex justify-content-between">
                                    <h5>
                                        Penjadwalan Ujian
                                    </h5>
                                    <button class="btn btn-info addBtn" data-bs-toggle="modal"
                                        data-bs-target="#addModal">
                                        <i class="fa fa-plus text-lg"> </i>
                                        Tambah Ujian

                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <?php foreach ($ujian as $uji): ?>
            <div class="col-xl-3 col-sm-6 my-2">
                <a href="<?= base_url($role . '/ujian/' . $uji['id']); ?>">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class=" text-md font-weight-bolder"> <?= $uji['judul']; ?> TA <?= $uji['tahun_ajaran']; ?></p> </span>
                                        </h5>
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                            Semester : <?= $uji['semester']; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                        <i class="fas fa-user-graduate text-lg opacity-10" aria-hidden="true"></i>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveJarumText">Tambah Ujian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url($role . '/tambahIndukUjian') ?>" method="post">

                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Judul</label>
                            <input type="text" name="judul" id="" class="form-control" placeholder="ujian tengah/akhir semester">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Tahun Ajaran</label>
                            <input type="text" name="tahun" id="" class="form-control" placeholder="masukan tahun ajaran seperti 2025-2026">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Semester</label> <br>
                            <input type="radio" name="semester" value="1" checked> Semester 1
                            <input type="radio" name="semester" value="2"> Semester 2 <br>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="" class="btn btn-info">Simpan</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <?= $this->endSection() ?>