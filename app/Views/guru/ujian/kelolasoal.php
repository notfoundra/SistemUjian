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
                    <div class="d-flex justify-content-between">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold">SMPN 1 Karangkancana</p>
                            <h5 class="font-weight-bolder mb-0">
                                Ujian
                            </h5>
                        </div>
                        <div class="div">
                            <button class="btn btn-info addBtn" data-bs-toggle="modal"
                                data-bs-target="#addModal">
                                <i class="fa fa-plus text-lg"> </i>
                                Tambah Soal

                            </button>
                            <button class="btn btn-info " data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-file-upload"></i>
                                Upload Soal

                            </button>
                            <a href="<?= base_url($role . '/download-template'); ?>" class="btn btn-info">
                                <i class="fas fa-file-download"></i> Download Template Soal
                            </a>
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
                            Soal Ujian <?= $ujian['mapel'] ?> Kelas <?= $ujian['kelas'] ?>
                        </h5>
                        <form action="<?= base_url($role . '/deleteAllSoal') ?>" method="post">
                            <input type="hidden" name="id_ujian" value="<?= $idUjian ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin mau hapus semua soal dan jawabannya?')">
                                <i class="fa fa-trash"></i> Hapus Semua Soal & Jawaban
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="row">
                        <div class="table-responsive">
                            <table id="dataTable" class="display compact " style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Soal</th>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Jawaban</th>
                                        <th class="text-uppercase text-center text-dark text-xxs font-weight-bolder opacity-7 ps-2">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($soal)): ?>
                                        <?php foreach ($soal as $que): ?>
                                            <tr class="text">
                                                <td class="text-center"><?= $que['pertanyaan'] ?></td>
                                                <td>
                                                    <?php if (!empty($que['jawaban'])): ?>
                                                        <ol class="list" style="list-style-type: upper-alpha;">
                                                            <?php foreach ($que['jawaban'] as $jawab): ?>
                                                                <li>
                                                                    <?= $jawab['benar'] ? '<p class="text-uppercase text-success font-weight-bolder ">' . $jawab['jawaban'] . '</p>' : $jawab['jawaban']; ?>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ol>
                                                    <?php else: ?>
                                                        <span class="text-danger">Belum ada jawaban</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm text-white bg-warning edit-btn mr-2"
                                                        data-id="<?= $que['id']; ?>"
                                                        data-pertanyaan="<?= $que['pertanyaan']; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal">
                                                        <i class="fa fa-pen text-lg"></i>
                                                    </button>
                                                    <button class="btn btn-sm text-white bg-danger stop-btn mr-2"
                                                        data-id="<?= $que['id']; ?>"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#confirmStopModal">
                                                        <i class="fa fa-trash text-lg"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-danger">Belum ada soal yang ditambahkan</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="confirmStopModal" tabindex="-1" aria-labelledby="confirmStopModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmStopModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete?</p>
                    <form action="<?= base_url($role . '/deleteSoal') ?>" method="post">
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
                    <h5 class="modal-title" id="moveJarumText">Tambah Soal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url($role . '/tambahSoal') ?>" method="post">

                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pertanyaan</label>
                            <input type="text" name="pertanyaan" id="" class="form-control">
                            <input type="text" name="id" id="" class="form-control" value="<?= $idUjian ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Jawaban Benar</label>
                            <input type="text" name="benar" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 1</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 2</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 3</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
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
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveJarumText">Edit Guru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url($role . '/editSoal') ?>" method="post">

                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pertanyaan</label>
                            <input type="text" name="pertanyaan" id="" class="form-control">
                            <input type="text" name="id" id="" class="form-control" value="<?= $idUjian ?>" hidden>
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Jawaban Benar</label>
                            <input type="text" name="benar" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 1</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 2</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Pilihan 3</label>
                            <input type="text" name="pilihan[]" id="" class="form-control">
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
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModal" aria-hidden="true">
        <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Import Soal</h5>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body align-items-center">
                    <div class="row align-items-center">
                        <div id="drop-area" class="border rounded d-flex justify-content-center align-item-center mx-3" style="height:200px; width: 95%; cursor:pointer;">
                            <div class="text-center mt-5">
                                <i class="ni ni-cloud-upload-96" style="font-size: 48px;">

                                </i>
                                <p class="mt-3" style="font-size: 28px;">
                                    Upload file here
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-9 pl-0">

                            <form action="<?= base_url($role . '/importSoal') ?>" id="modalForm" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_ujian" value="<?= $idUjian ?>">
                                <input type="file" id="fileInput" name="file" multiple accept=".xls , .xlsx" class="form-control ">
                        </div>
                        <div class="col-3 pl-0">
                            <button type="submit" class="btn btn-info btn-block"> Simpan</button>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            "language": {
                "emptyTable": "Belum ada soal yang tersedia"
            }
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
                var pertanyaan = $(this).data('pertanyaan');

                // Isi modal dengan data yang diambil dari tombol
                $('#editModal input[name="id"]').val(id);
                $('#editModal input[name="pertanyaan"]').val(pertanyaan);

                // Ambil data jawaban via AJAX
                $.ajax({
                    url: '<?= base_url($role . "/getSoal") ?>',
                    type: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            $('#editModal input[name="benar"]').val(response.benar);
                            $('#editModal input[name="pilihan[]"]').each(function(index) {
                                $(this).val(response.pilihan[index] ?? '');
                            });
                        } else {
                            alert('Gagal mengambil data soal.');
                        }
                    }
                });

                $('#editModal').modal('show');
            });

            // Submit form pakai AJAX
            $('#editSoalForm').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?= base_url($role . "/editSoal") ?>',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 'success') {
                            alert('Soal berhasil diperbarui!');
                            location.reload(); // Refresh halaman
                        } else {
                            alert('Gagal mengedit soal.');
                        }
                    }
                });
            });


        });
    </script>
    <?= $this->endSection() ?>