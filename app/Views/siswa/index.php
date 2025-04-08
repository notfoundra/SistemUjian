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
                        <div class="col-6">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">SMPN 1 Karangkancana</p>
                                <h5 class="font-weight-bolder mb-0">
                                    Selamat Datang <?= $user ?>
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                    Rangking</p>
                                <h5 class="font-weight-bolder mb-0">

                                    <?= $rank ?>
                                    <span class=" text-sm font-weight-bolder">dari <?= $jumsiswa ?> siswa</span>
                                </h5>
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
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">IPK </p>
                                <h5 class="font-weight-bolder mb-0" id="deffectRate">

                                    <span class=" text-sm font-weight-bolder"><?= $ipk ?></span>
                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">

                                <i class="fas fa-chalkboard-teacher text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Ujian Terjadwal</p>
                                <h5 class="font-weight-bolder mb-0" id="output">

                                    <span class=" text-sm font-weight-bolder"><?= $scheduled ?> </span>

                                </h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-tasks text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Ujian Dikerjakan</p>
                                <h5 class="font-weight-bolder mb-0" id="pph">
                                </h5>
                                <span class=" text-sm font-weight-bolder"><?= $expired ?></span> Ujian
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="ni ni-check-bold text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-6">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold"></p>
                                <h6 class="font-weight-bolder mb-0">
                                    Jadwal Ujian Anda
                                </h6>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php
    $tanggalHariIni = date('Y-m-d'); // Ambil tanggal hari ini
    ?>

    <?php foreach ($ujian as $kelas => $tanggalUjian): ?>
        <div class="row">
            <div class="col-xl-12 col-sm-12 my-2">
                <div class="card">
                    <div class="card-header">
                        <h4><?= htmlspecialchars($kelas); ?></h4> <!-- Nama Kelas -->
                    </div>
                    <div class="card-body p-3">
                        <table class="table">
                            <thead>
                                <tr>
                                    <?php foreach (array_keys($tanggalUjian) as $tanggal): ?>
                                        <th><?= htmlspecialchars($tanggal); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach ($tanggalUjian as $tanggal => $items): ?>
                                        <td>
                                            <?php foreach ($items as $data): ?>
                                                <div class="mb-2 p-2 border rounded">
                                                    <strong>Mapel :</strong> <?= $data['mapel'] ?><br>
                                                    <strong>Jam :</strong> <?= $data['jam'] ?><br>
                                                    <strong>Durasi :</strong> <?= $data['durasi'] ?> menit<br>
                                                    <strong>Nilai :</strong> <?= $data['nilai'] ?? '-' ?> /100<br>

                                                    <?php
                                                    // Konversi format tanggal dari "D, d M Y" ke "Y-m-d"
                                                    $tanggalConvert = date('Y-m-d', strtotime($tanggal));

                                                    if ($tanggalConvert > $tanggalHariIni) {
                                                        $buttonText = "Ujian Belum Dimulai";
                                                        $buttonClass = "disabled btn-secondary"; // Warna abu-abu
                                                    } elseif ($tanggalConvert < $tanggalHariIni) {
                                                        $buttonText = "Ujian Berakhir";
                                                        $buttonClass = "disabled btn-danger"; // Warna merah
                                                    } elseif ($data['nilai'] != null) {
                                                        $buttonText = "Ujian selesai";
                                                        $buttonClass = "btn-info disabled";
                                                    } else {
                                                        $buttonText = "Mulai Ujian";
                                                        $buttonClass = "btn-info"; // Warna biru
                                                    }
                                                    ?>

                                                    <a href="<?= base_url($role . '/startTest/' . $data['id_ujian']) ?>"
                                                        class="btn btn-sm <?= $buttonClass ?>">
                                                        <?= $buttonText ?>
                                                        <i class="fas <?= ($tanggalConvert == $tanggalHariIni) ? 'fa-play' : 'fa-times' ?>"></i>
                                                    </a>

                                                </div>
                                            <?php endforeach; ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>



    <?= $this->endSection() ?>