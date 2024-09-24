<?php
date_default_timezone_set("Asia/Jakarta");

if(isset($_POST['proses'])){

    // mengambil data dari form
    $nmpasien = $_POST['nmpasien'];
    $tgl=date("Y/m/d");

    // Proses simpan konsultasi
    $sql = "INSERT INTO konsultasi VALUES (NULL, '$tgl','$nmpasien')";
    mysqli_query($conn, $sql);
    
    // Mengambil idgejala
    $digejala=$_POST['idgejala'];
    
     // Proses mengambil data konsultasi
     $sql = "SELECT * FROM konsultasi ORDER BY idkonsultasi DESC";
     $result = $conn->query($sql);
     $row = $result->fetch_assoc();
     $idkonsultasi = $row['idkonsultasi'];

     // Proses simpan detail konsultasi
     $idgejala = $_POST['idgejala'];
     $jumlah = count($idgejala);
     for ($i = 0; $i < $jumlah; $i++) {
         $idgejalane = $idgejala[$i];
         $sql_detail = "INSERT INTO detail_konsultasi VALUES ($idkonsultasi, '$idgejalane')";
         mysqli_query($conn, $sql_detail);
     }

     // mengambil data dari tabel penyakit untuk di cek di basis aturan
     $sql = "SELECT*FROM penyakit";
     $result = $conn->query($sql);
     while($row = $result->fetch_assoc()) {
        $idpenyakit = $row['idpenyakit'];
        $jyes=0;

        // mencari jumlah gejala di basis aturan berdasarkan penyakit
        $sql2 = "SELECT COUNT(idpenyakit) AS jml_gejala 
                FROM basis_aturan INNER JOIN detail_basis_aturan 
                ON basis_aturan.idaturan=detail_basis_aturan.idaturan
                WHERE idpenyakit='$idpenyakit'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $jml_gejala = $row2['jml_gejala'];

        // mencari gejala pada basis aturan
        $sql3 = "SELECT idpenyakit,idgejala
            FROM basis_aturan INNER JOIN detail_basis_aturan 
            ON basis_aturan.idaturan=detail_basis_aturan.idaturan
            WHERE idpenyakit='$idpenyakit'";
        $result3 = $conn->query($sql3);
        while($row3 = $result3->fetch_assoc()) {
        
            $idgejalane=$row3['idgejala'];

            // membandingkan apakah yang dipilih pada konsultasi sesuai
            $sql4 = "SELECT idgejala FROM detail_konsultasi 
                    WHERE idkonsultasi='$idkonsultasi' AND idgejala='$idgejalane'";
            $result4 = $conn->query($sql4);
            if ($result4->num_rows > 0) {
                $jyes+=1;
            }

        }

        // MENCARI PERSENTASE
        if($jml_gejala>0){
            $peluang = round(($jyes/$jml_gejala)*100,2);
        }else{
            $peluang = 0;
        }

        // simpan data detail penyakit
        if($peluang>0){
            $sql = "INSERT INTO detail_penyakit VALUES ($idkonsultasi, '$idpenyakit','$peluang')";
            mysqli_query($conn, $sql);
        }

        header("Location:?page=konsultasi&action=hasil&idkonsultasi=$idkonsultasi");
     } 
}

?>

<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST" name="Form" onsubmit="return validasiForm()">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark">
                        <strong>Konsultasi penyakit</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Nama Pasien</label>
                            <input type="text" class="form-control" name="nmpasien" maxlength="50" required>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $sql = "SELECT * FROM gejala ORDER BY nmgejala ASC";
                                    $result = $conn->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="check-item" name="idgejala[]" value="<?php echo $row['idgejala']; ?>">
                                            </td>
                                            <td><?php echo $no++; ?></td>
                                            <td><?php echo $row['nmgejala']; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <input class="btn btn-primary" type="submit" name="proses" value="Proses">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>


<script type="text/javascript">
    function validasiForm()
    {

        // validasi gejala yang belum dipilih
        var checkboxes = document.getElementsByName('<?php echo 'idgejala[]'; ?>');

        var isChecked = false;

        for(var i=0; i < checkboxes.length; i++){
            if(checkboxes[i].checked){
                isChecked = true;
                break;
            }           
        }

        // jika belum ada yang di check
        if(!isChecked){
            alert('Pilih setidaknya satu gejala !!');
            return false;           
        }

        return true;
    }
</script>