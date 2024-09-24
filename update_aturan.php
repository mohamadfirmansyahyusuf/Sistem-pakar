<?php
// Mengambil idaturan dari parameter GET dan melakukan sanitasi
$idaturan = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil data berdasarkan idaturan
$sql = "SELECT basis_aturan.idaturan, basis_aturan.idpenyakit, penyakit.nmpenyakit 
        FROM basis_aturan 
        INNER JOIN penyakit ON basis_aturan.idpenyakit = penyakit.idpenyakit 
        WHERE idaturan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idaturan);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Proses update
if (isset($_POST['update'])) {
    $idgejala = isset($_POST['idgejala']) ? $_POST['idgejala'] : array();

    if (!empty($idgejala)) {
        $jumlah = count($idgejala);
        for ($i = 0; $i < $jumlah; $i++) {
            $idgejalane = intval($idgejala[$i]);
            $sql_detail = "INSERT INTO detail_basis_aturan (idaturan, idgejala) VALUES (?, ?)";
            $stmt_detail = $conn->prepare($sql_detail);
            $stmt_detail->bind_param("ii", $idaturan, $idgejalane);
            $stmt_detail->execute();
        }
    }
    header("Location:?page=aturan");
    exit();
}
?>

<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark">
                        <strong>Update Data Basis Aturan</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Nama Penyakit</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['nmpenyakit']); ?>" name="nmpenyakit" readonly>
                        </div>

                        <!-- Tabel data Gejala -->
                        <div class="form-group">
                            <label for="">Pilih gejala-gejala berikut</label>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="30px"></th>
                                        <th width="30px">No.</th>
                                        <th width="700px">Nama Gejala</th>
                                        <th width="50px"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Query untuk mengambil semua data gejala
                                    $sql_gejala = "SELECT * FROM gejala ORDER BY nmgejala ASC";
                                    $result_gejala = $conn->query($sql_gejala);
                                    while ($row_gejala = $result_gejala->fetch_assoc()) {
                                        $idgejala = $row_gejala['idgejala'];

                                        // Cek ke tabel detail basis aturan
                                        $sql2 = "SELECT * FROM detail_basis_aturan WHERE idaturan=? AND idgejala=?";
                                        $stmt2 = $conn->prepare($sql2);
                                        $stmt2->bind_param("ii", $idaturan, $idgejala);
                                        $stmt2->execute();
                                        $result2 = $stmt2->get_result();
                                        if ($result2->num_rows > 0) {
                                            // Jika ditemukan maka tampilkan datanya dengan checklist tidak aktif dan tombol hapus
                                            echo "<tr>
                                                <td align='center'><input type='checkbox' class='check-item' disabled='disabled'></td>
                                                <td>{$no}</td>
                                                <td>{$row_gejala['nmgejala']}</td>
                                                <td>
                                                    <a onclick=\"return confirm('Yakin menghapus data ini ?')\" class='btn btn-danger' href='?page=aturan&action=hapus_gejala&idaturan={$idaturan}&idgejala={$idgejala}'>
                                                        <i class='fas fa-window-close'></i>
                                                    </a>
                                                </td>
                                            </tr>";
                                        } else {
                                            // Jika tidak ditemukan maka checklist aktif
                                            echo "<tr>
                                                <td align='center'><input type='checkbox' class='check-item' name='idgejala[]' value='{$idgejala}'></td>
                                                <td>{$no}</td>
                                                <td>{$row_gejala['nmgejala']}</td>
                                                <td><i class='fas fa-window-close'></i></td>
                                            </tr>";
                                        }
                                        $no++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <input class="btn btn-primary" type="submit" name="update" value="Update">
                        <a class="btn btn-danger" href="?page=aturan">Batal</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php
// Tutup koneksi di akhir skrip
$conn->close();
?>