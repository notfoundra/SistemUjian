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
                                                    <a href="#" class="btn btn-danger btn-sm  deleteBtn"
                                                        data-id="<?= $data['id_ujian'] ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-warning btn-sm editBtn" data-id="<?= $data['id_ujian'] ?>">
                                                        <i class="fas fa-edit"></i>
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



    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveJarumText">Tambah Ujian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?= base_url($role . '/tambahUjian') ?>" method="post">
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Kelas</label>
                            <input type="text" name="id_induk" id="" value="<?= $idInduk ?>" hidden>
                            <select name="kelas" id="kelas" class="form-control" onchange="getMapel()">
                                <option value="">----</option>
                                <?php foreach ($listKelas as $kls): ?>
                                    <option value="<?= $kls['id'] ?>"><?= $kls['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Mapel</label>
                            <select name="mapel" id="mapel" class="form-control">
                                <option value="">----</option>

                            </select>
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Waktu</label> <br>
                            <input type="datetime-local" name="waktu" id="waktu" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Durasi</label> <br>
                            <input type="number" name="durasi" id="" class="form-control" placeholder="menit">
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
    <!-- Modal Edit Ujian -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="edit_id" name="id">

                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Kelas</label>
                            <input type="text" name="id_induk" id="" value="<?= $idInduk ?>" hidden>
                            <select name="kelas" id="kelasEdit" class="form-control" onchange="getMapelEdit()">
                                <option value="">----</option>
                                <?php foreach ($listKelas as $kls): ?>
                                    <option value="<?= $kls['id'] ?>"><?= $kls['nama'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="waliKelas" class="form-control-label">Mapel</label>
                            <select name="mapel" id="mapelEdit" class="form-control">
                                <option value="">----</option>

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="edit_tanggal">Tanggal</label>
                            <input type="datetime-local" id="edit_tanggal" name="tanggal" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="edit_durasi">Durasi</label>
                            <input type="number" id="edit_durasi" name="durasi" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal Delete Konfirmasi -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Hapus Ujian</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus ujian ini?</p>
                    <input type="hidden" id="delete_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <script>
        function getMapel() {
            var kelas_id = document.getElementById("kelas").value; // Ambil ID kelas yang dipilih
            console.log(kelas_id)
            if (kelas_id === "") {
                document.getElementById("mapel").innerHTML = "<option value=''>----</option>";
                return;
            }

            fetch("<?= base_url($role . '/getMapelByKelas') ?>/" + kelas_id)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Cek apakah data muncul di console

                    let mapelSelect = document.getElementById("mapel");

                    // Reset opsi mapel
                    mapelSelect.innerHTML = "<option value=''>----</option>";

                    // Tambahkan opsi dari hasil AJAX
                    data.forEach(mapel => {
                        let option1 = document.createElement("option");

                        option1.value = mapel.id;
                        option1.textContent = mapel.nama;
                        mapelSelect.appendChild(option1);
                    });
                })
                .catch(error => console.error("Error fetching mapel:", error));

        }

        function getMapelEdit() {
            var kelas_id = document.getElementById("kelasEdit").value;

            if (kelas_id === "") {
                document.getElementById("mapelEdit").innerHTML = "<option value=''>----</option>";
                return;
            }

            fetch("<?= base_url($role . '/getMapelByKelas') ?>/" + kelas_id)
                .then(response => response.json())
                .then(data => {
                    let mapelSelect = document.getElementById("mapelEdit");

                    mapelSelect.innerHTML = "<option value=''>----</option>";

                    data.forEach(mapel => {
                        let option = document.createElement("option");
                        option.value = mapel.id;
                        option.textContent = mapel.nama;
                        mapelSelect.appendChild(option);
                    });
                }).catch(error => console.error("Error fetching mapel:", error));
        }
    </script>
    <script>
        $(document).ready(function() {

            // EDIT: Ambil data dan tampilkan modal edit
            $(".editBtn").click(function() {
                let id = $(this).data("id");
                $.ajax({
                    url: "<?= base_url($role . '/getUjian') ?>/" + id,
                    type: "GET",
                    dataType: "json",
                    success: function(data) {
                        if (data.status !== 'error') {
                            $("#edit_id").val(data.id);
                            $("#edit_tanggal").val(data.tanggal);
                            $("#edit_durasi").val(data.durasi);
                            $("#editModal").modal("show");
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops!',
                                text: 'Data tidak ditemukan.',
                            });
                        }
                    }
                });
            });

            // SUBMIT Edit Form
            $("#editForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: "<?= base_url($role . '/updateUjian') ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data berhasil diperbarui!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            });

            // DELETE: Tampilkan modal konfirmasi
            $(".deleteBtn").click(function() {
                let id = $(this).data("id");
                $("#delete_id").val(id);
                $("#deleteModal").modal("show");
            });

            // KONFIRMASI DELETE
            $("#confirmDelete").click(function() {
                let id = $("#delete_id").val();
                $.ajax({
                    url: "<?= base_url($role . '/deleteujian') ?>/" + id,
                    type: "POST",
                    success: function(response) {
                        if (response.status === 'deleted') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Data berhasil dihapus!',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    }
                });
            });

        });
    </script>



    <?= $this->endSection() ?>