<?php 
// Mengambil id dari parameter
$idkonsultasi = $_GET['idkonsultasi'];

// Pastikan koneksi ke database berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Kueri untuk mengambil data konsultasi berdasarkan id
$sql = "SELECT * FROM konsultasi WHERE idkonsultasi= '$idkonsultasi'";
$result = $conn->query($sql);

// Pastikan kueri berhasil dijalankan dan data ditemukan
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Data tidak ditemukan atau terjadi kesalahan: " . $conn->error;
    exit;
}
?>

<!-- Tampilan halaman hasil konsultasi -->
<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark"><strong>Hasil Konsultasi</strong></div>
                    <div class="card-body">

                        <div class="form-group">
                            <label for="">Nama Pasien</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($row['nama'], ENT_QUOTES, 'UTF-8'); ?>" name="nama" readonly>
                        </div> 
                       
                        <!-- Tabel gejala-gejala -->
                         <label for="">Gejala-Gejala Penyakit Yang dipilih</label>
                         <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="40px">NO.</th>
                            <th width="700px">Nama Gejala</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            $sql = "SELECT detail_konsultasi.idkonsultasi, detail_konsultasi.idgejala, gejala.nmgejala
                                    FROM detail_konsultasi 
                                    INNER JOIN gejala ON detail_konsultasi.idgejala = gejala.idgejala 
                                    WHERE idkonsultasi = '$idkonsultasi'";
                            $result = $conn->query($sql);
                            
                            // Pastikan kueri berhasil dijalankan
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nmgejala'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>    
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='2'>Tidak ada gejala yang ditemukan atau terjadi kesalahan: " . $conn->error . "</td></tr>";
                            }
                        ?>    
                        </tbody>
                        </table>

                        <!-- Hasil konsultasi penyakitnya -->
                         <label for="">Hasil Konsultasi Penyakit</label>
                         <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th width="40px">NO.</th>
                            <th width="150px">Nama Penyakit</th>
                            <th width="100px">Persentase</th>
                            <th width="700px">Solusi</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                            $no = 1;
                            $sql = "SELECT detail_penyakit.idkonsultasi, detail_penyakit.idpenyakit, penyakit.nmpenyakit, penyakit.solusi, detail_penyakit.persentase
                                    FROM detail_penyakit 
                                    INNER JOIN penyakit ON detail_penyakit.idpenyakit = penyakit.idpenyakit 
                                    WHERE idkonsultasi = '$idkonsultasi' ORDER BY persentase DESC";
                            $result = $conn->query($sql);
                            
                            // Pastikan kueri berhasil dijalankan
                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                        ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nmpenyakit'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($row['persentase'], ENT_QUOTES, 'UTF-8') . "%"; ?></td>
                                <td><?php echo htmlspecialchars($row['solusi'], ENT_QUOTES, 'UTF-8'); ?></td>
                            </tr>    
                        <?php
                                }
                            } else {
                                echo "<tr><td colspan='4'>Tidak ada penyakit yang ditemukan atau terjadi kesalahan: " . $conn->error . "</td></tr>";
                            }
                            $conn->close();
                        ?>    
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
