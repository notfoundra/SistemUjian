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
                                        Data Hasil Ujian
                                    </h5>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-header">
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Nama : <?= $iden['nama'] ?></p>
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Kelas : <?= $iden['kelas'] ?></p>
                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Rata rata nilai : <?= $ipk ?></p>
                </div>
                <div class="card-body">
                    <table class="table table-stripped text-center">
                        <thead>
                            <tr>
                                <th class="bg-secondary text-white">Mata Pelajaran</th>
                                <th class="bg-secondary text-white">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($nilai as $row): ?>
                                <tr>
                                    <td><?= $row['mapel'] ?></td>
                                    <td><?= $row['nilai'] ?? 0 ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <?= $this->endSection() ?>