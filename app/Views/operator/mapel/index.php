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
                                        Data Pelajaran
                                    </h5>
                                    <button class="btn btn-info addBtn" data-bs-toggle="modal"
                                        data-bs-target="#addModal">
                                        <i class="fa fa-plus text-lg"> </i>
                                        Tambah Pelajaran

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
        <?php foreach ($kelas as $kls): ?>
            <div class="col-xl-3 col-sm-6 my-2">
                <div class="card">
                    <a data-id="<?= $kls['id'] ?>" class="btn btn-lg detailBtn" data-bs-toggle="modal">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <h5 class="font-weight-bolder mb-0">
                                            <span class=" text-md font-weight-bolder"> <?= $kls['nama']; ?></p> </span>
                                        </h5>
                                        <p class="text-sm mb-0 text-capitalize font-weight-bold">
                                            Wali Kelas : <?= $kls['wali_kelas']; ?>
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
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveJarumText">Tambah Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url($role . '/tambahMapel') ?>" method="post">

                        <div class="form-group">
                            <label for="mapel" class="form-control-label">Nama</label>
                            <input type="text" name="nama" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="kelas" class="form-control-label">Kelas</label>
                            <select name="kelas_id" id="jarumname" class="form-control">
                                <option value="">----</option>
                                <?php foreach ($kelas as $kls): ?>
                                    <option value="<?= $kls['id'] ?>"><?= $kls['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelas" class="form-control-label">Guru Pengajar</label>
                            <select name="guru" id="jarumname" class="form-control">
                                <option value="">----</option>
                                <?php foreach ($guru as $gr): ?>
                                    <option value="<?= $gr['id'] ?>"><?= $gr['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="" class="btn btn-info">Pindah</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade modal-lg" id="detailModal" tabindex="-1" aria-labelledby="detailModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title headerText" id="moveJarumText">Tambah Mapel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body detailMapel">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
        document.addEventListener('DOMContentLoaded', function() {
            $('.detailBtn').click(function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?= base_url("operator/mapelPerkelas") ?>',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        id: id
                    },
                    success: function(response) {
                        console.log("Response yang diterima:", response);

                        // Perbaiki pengecekan data
                        if (!response || response.length === 0) {
                            console.error("Data kosong atau tidak ditemukan.");
                            return;
                        }

                        $('#detailModal').modal('show');
                        document.querySelector(".headerText").innerHTML = `<h5> Detail Mapel </h5>`;

                        // Perbaikan `base_url()`
                        document.querySelector(".detailMapel").innerHTML = `
                    <table id="mapelTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${response.map(item => `
                                <tr>
                                    <td>${item.nama}</td>
                                    <td>${item.guru}</td>
                                    <td>
                                        <a href="<?= base_url() ?>operator/hapusMapel/${item.id}" class="btn btn-danger"> Hapus </a>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table> 
                `;
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                    }
                });
            });
        });
    </script>
    <?= $this->endSection() ?>