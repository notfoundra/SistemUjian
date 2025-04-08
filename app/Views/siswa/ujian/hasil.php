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
        <?php foreach ($ujian as $uji): ?>
            <div class="col-xl-3 col-sm-6 my-2">
                <a href="<?= base_url($role . '/hasilujian/' . $uji['id']); ?>">
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

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <?= $this->endSection() ?>