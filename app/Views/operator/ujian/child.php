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
                                        Tambah Mata Pelajaran

                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php foreach ($ujian as $kelas => $tanggalUjian): ?>
        <div class="row">
            <div class="col-xl-12 col-sm-12 my-2">
                <div class="card">
                    <div class="card-header">
                        <h4><?= htmlspecialchars($kelas); ?></h4> <!-- Nama Kelas -->
                    </div>
                    <div class="card-body p-3">
                        <table class="table" id="Datatable">
                            <thead>
                                <tr>

                                    <?php foreach ($tanggalUjian as $tanggal => $items): ?>
                                        <th><?= htmlspecialchars($tanggal); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php foreach ($tanggalUjian as $tanggal => $items): ?>
                                    <?php foreach ($items as $data): ?>
                                        <tr>
                                            <td>
                                                <input type="text " value="<?= $data['id_ujian'] ?>" hidden>
                                                Mapel : <?= $data['mapel'] ?><br>
                                                Jam : <?= $data['jam'] ?><br>
                                                Durasi :<?= $data['durasi'] ?><br>
                                                <a href="" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                <a href="" class="btn btn-warning">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>



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