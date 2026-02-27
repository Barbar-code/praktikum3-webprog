<div class="container mt-5">
    <?php Flasher::flash(); ?>

    <div class="row">
        <div class="col-12">
            <div class="glass-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-secondary pb-3">
                    <h3 class="text-light m-0 fw-light" style="letter-spacing: 2px;">Data Mahasiswa</h3>
                    <button type="button" class="btn btn-outline-light btn-glass tombolTambahData" data-bs-toggle="modal" data-bs-target="#formModal">
                        + Tambah Data
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="tabelMahasiswa" class="table table-borderless glass-table w-100 align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NRP</th>
                                <th>Email</th>
                                <th>Jurusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach( $data['mhs'] as $mhs ) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($mhs['nama']); ?></td>
                                <td><?= htmlspecialchars($mhs['nrp']); ?></td>
                                <td><?= htmlspecialchars($mhs['email']); ?></td>
                                <td><span class="badge glass-badge"><?= htmlspecialchars($mhs['jurusan']); ?></span></td>
                                <td>
                                    <a href="<?= BASEURL; ?>/mahasiswa/ubah/<?= $mhs['id']; ?>" class="btn btn-sm btn-outline-light btn-glass tampilModalUbah" data-bs-toggle="modal" data-bs-target="#formModal" data-id="<?= $mhs['id']; ?>">Ubah</a>
                                    <a href="<?= BASEURL; ?>/mahasiswa/hapus/<?= $mhs['id']; ?>" class="btn btn-sm btn-outline-danger btn-glass btn-hapus" data-nama="<?= htmlspecialchars($mhs['nama']); ?>">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade glass-modal" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content glass-card border-0">
      <div class="modal-header border-bottom border-secondary">
        <h5 class="modal-title text-light fw-light" id="formModalLabel" style="letter-spacing: 1px;">Tambah Data Mahasiswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-light px-4">
        <form action="<?= BASEURL; ?>/mahasiswa/tambah" method="post">
            <input type="hidden" name="id" id="id">
            <div class="mb-4">
                <label for="nama" class="form-label text-muted small text-uppercase fw-bold tracking-wide">Nama Lengkap</label>
                <input type="text" class="form-control glass-input" id="nama" name="nama" required>
            </div>
            <div class="mb-4">
                <label for="nrp" class="form-label text-muted small text-uppercase fw-bold tracking-wide">NRP</label>
                <input type="number" class="form-control glass-input" id="nrp" name="nrp" required>
            </div>
            <div class="mb-4">
                <label for="email" class="form-label text-muted small text-uppercase fw-bold tracking-wide">Email</label>
                <input type="email" class="form-control glass-input" id="email" name="email" required>
            </div>
            <div class="mb-4">
                <label for="jurusan" class="form-label text-muted small text-uppercase fw-bold tracking-wide">Jurusan</label>
                <select class="form-select glass-input" id="jurusan" name="jurusan">
                    <option value="Teknik Informatika">Teknik Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Mesin">Teknik Mesin</option>
                    <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                </select>
            </div>
      </div>
      <div class="modal-footer border-top border-secondary">
        <button type="button" class="btn btn-outline-secondary btn-glass" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-outline-light btn-glass px-4">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
