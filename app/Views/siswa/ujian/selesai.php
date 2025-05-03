<?= $this->extend($role . '/layout') ?>

<?= $this->section('content') ?>
<style>
    .sticky-card {
        position: sticky;
        top: 0;
        z-index: 1050;
        /* Biar nggak ketutup elemen lain */
        background: white;
        /* Biar nggak transparan */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Biar ada efek floating */
    }
</style>

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

    <div class="row">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4 mt-2">

            <div class="card">
                <div class="card-header">
                    <div class="text-center">
                        <p class=" text-sm mb-0 text-capitalize font-weight-bold"> <?= $name['judul'] ?></p>
                        <h5 class="font-weight-bolder mb-0">
                            <?= $name['mapel'] ?>
                            <?= $name['kelas'] ?>
                        </h5>
                    </div>
                </div>
                <div class="card-body text-center">
                    <h5>
                        Hasil Ujian <?= $user ?>
                    </h5>
                    <br>
                    <h1 class="font-weight-bolder mb-0" id="output">
                        <?= $nilai['nilai'] ?>
                        <span class=" text-sm font-weight-bolder"> /100</span>

                    </h1>
                </div>
                <div class="card-footer text-center">
                    <a href="<?= base_url($role . '/') ?>" class="btn btn-info">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>