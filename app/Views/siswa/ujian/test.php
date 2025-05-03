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

    <div class="row my-4">
        <div class="col-xl-12 col-sm-12 mb-xl-0 mb-4">
            <div class="card sticky-card shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between">
                        <div class="numbers">
                            <p class="text-sm mb-0 text-capitalize font-weight-bold"> <?= $name['judul'] ?></p>
                            <h5 class="font-weight-bolder mb-0">
                                <?= $name['mapel'] ?>
                                <?= $name['kelas'] ?>
                            </h5>
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
                    </div>
                </div>
                <form action="<?= base_url($role . '/jawaban/' . $idUjian) ?>" id="examForm" method="post">
                    <input type="text" name="siswaid" id="" value="<?= $userid ?>" hidden>
                    <div class="card-body p-3">
                        <?php if (!empty($soal)): ?>
                            <?php foreach ($soal as $index => $que): ?>
                                <div class="row mb-3 mx-4">

                                    <h4><?= $que['pertanyaan'] ?></h4>

                                    <?php if (!empty($que['jawaban'])): ?>
                                        <div class="col-mx-3">
                                            <ol class="list" style="list-style-type: upper-alpha;">
                                                <?php foreach ($que['jawaban'] as $key => $jawab): ?>
                                                    <li>
                                                        <label>
                                                            <input type="radio" name="jawaban_<?= $index; ?>" value="<?= $jawab['benar']; ?>">
                                                            <?= $jawab['jawaban']; ?>
                                                        </label>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ol>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-danger">Belum ada jawaban</span>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <h3>Belum ada soal yang ditambahkan</h3>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <div class="row">

                            <button type="submit" class="btn-info btn">
                                submit
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="startExamModal" tabindex="-1" aria-labelledby="startExamLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="startExamLabel">Konfirmasi Mulai Ujian</h5>
            </div>
            <div class="modal-body">
                <p>Apakah Anda siap untuk memulai ujian?</p>
            </div>
            <div class="modal-footer">
                <div class="row">

                    <button type="button" class="btn btn-primary btn-block" id="startExamBtn">Mulai Ujian</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let duration = <?= $durasi ?> * 60; // Durasi dalam detik
        let timerElement = document.getElementById("timeLeft");
        let examTimer = document.getElementById("examTimer");
        let interval;

        // Tampilkan modal otomatis saat halaman dimuat
        let startModal = new bootstrap.Modal(document.getElementById("startExamModal"));
        startModal.show();

        document.getElementById("startExamBtn").addEventListener("click", function() {
            let elem = document.documentElement;
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) { // Firefox
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) { // Chrome, Safari, Edge
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) { // IE/Edge
                elem.msRequestFullscreen();
            }

            startModal.hide();
            examTimer.classList.remove("d-none");
            startTimer();
        });

        function startTimer() {
            interval = setInterval(function() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;
                timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                if (duration <= 0) {
                    clearInterval(interval);
                    alert("Waktu habis! Ujian akan dikumpulkan.");
                    document.getElementById("examForm").submit();
                }
                duration--;
            }, 1000);
        }
        document.addEventListener("fullscreenchange", function() {
            if (!document.fullscreenElement) {
                alert("Kamu keluar dari fullscreen! Ujian akan dikumpulkan.");
                document.getElementById("examForm").submit();
            }
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector("form");
        const submitButton = document.querySelector("button[type='submit']");
        const soalGroups = document.querySelectorAll("ol.list");

        function checkAnswers() {
            let allAnswered = true;

            soalGroups.forEach((group, index) => {
                const radios = group.querySelectorAll(`input[name="jawaban_${index}"]`);
                const isChecked = [...radios].some(radio => radio.checked);

                if (!isChecked) {
                    allAnswered = false;
                }
            });

            submitButton.disabled = !allAnswered;
        }

        form.addEventListener("change", checkAnswers);
        checkAnswers(); // Cek saat pertama kali halaman dimuat
    });
</script>

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