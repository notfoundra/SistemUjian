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
                                    Data Kelas
                                </h5>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4 mt-2">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between">
                        <h5>
                            Data Siswa Kelas <?= $kelas['nama']; ?>
                        </h5>
                        <button class="btn btn-info addBtn" data-bs-toggle="modal"
                            data-bs-target="#addModal">
                            <i class="fa fa-plus text-lg"> </i>
                            Tambah Siswa

                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="dataTable" class="display compact striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Nama</th>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Kelas</th>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($siswa as $sis) : ?>
                                        <tr class="text-center">
                                            <td><?= $sis['nama'] ?></td>
                                            <td><?= $sis['email'] ?? 'belum ada wali kelas' ?></td>
                                            <td><?= $sis['kelas'] ?? 'belum ada wali kelas' ?></td>
                                            <td>

                                                <button class="btn btn-sm text-white bg-warning edit-btn  mr-2"
                                                    data-id="<?= $sis['id']; ?>"
                                                    data-nama="<?= $sis['nama'] ?>"
                                                    data-email="<?= $sis['email'] ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editModal">
                                                    <i class="fa fa-pen text-lg"></i>
                                                </button>
                                                <button class="btn btn-sm text-white bg-danger stop-btn  mr-2"
                                                    data-id="<?= $sis['id']; ?>"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#confirmStopModal">
                                                    <i class="fa fa-trash text-lg"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="modal fade" id="confirmStopModal" tabindex="-1" aria-labelledby="confirmStopModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="confirmStopModalLabel">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete?</p>
                                            <form action="<?= base_url($role . '/deleteSiswa') ?>" method="post">
                                                <input type="hidden" id="stopPlanId" name="id">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="moveJarumText">Tambah Kelas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?= base_url($role . '/tambahSiswa') ?>" method="post">

                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Nama</label>
                                                    <input type="text" name="nama" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Email</label>
                                                    <input type="email" name="email" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Password</label>
                                                    <input type="password" name="password" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Kelas</label>
                                                    <select name="kelas" id="jarumname" class="form-control">
                                                        <option value="">----</option>
                                                        <?php foreach ($listkelas as $kls): ?>
                                                            <option value="<?= $kls['id'] ?>"><?= $kls['nama'] ?></option>
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
                            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="moveJarumText">Edit Guru</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="<?= base_url($role . '/editSiswa') ?>" method="post">

                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Nama</label>
                                                    <input type="text" name="id" id="" class="form-control" hidden>
                                                    <input type="text" name="nama" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Email</label>
                                                    <input type="email" name="email" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Password</label>
                                                    <input type="password" name="password" id="" class="form-control">
                                                </div>
                                                <div class="form-group">
                                                    <label for="waliKelas" class="form-control-label">Kelas</label>
                                                    <select name="kelas_id" id="jarumname" class="form-control">
                                                        <option value="">----</option>
                                                        <?php foreach ($listkelas as $kls): ?>
                                                            <option value="<?= $kls['id'] ?>"><?= $kls['nama'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" id="" class="btn btn-info">Edit</button>
                                        </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
        document.addEventListener('DOMContentLoaded', function() {
            const stopButtons = document.querySelectorAll('.stop-btn');
            const editBtn = document.querySelectorAll('.edit-btn');

            const stopPlanIdInput = document.getElementById('stopPlanId');

            stopButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const planId = this.getAttribute('data-id');
                    stopPlanIdInput.value = planId; // Set ID ke input hidden
                });
            });
            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                var nama = $(this).data('nama');
                var email = $(this).data('email');


                $('#editModal').find('input[name="id"]').val(id);
                $('#editModal').find('input[name="nama"]').val(nama);
                $('#editModal').find('input[name="email"]').val(email);


                $('#editModal').modal('show');
                $('#editModal').find('form').attr('action', '<?= base_url($role . '/editSiswa') ?>');

            });



        });
    </script>
    <?= $this->endSection() ?>