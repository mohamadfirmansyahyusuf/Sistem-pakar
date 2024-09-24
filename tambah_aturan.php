<?php

if (isset($_POST['simpan'])) {

    // Mengambil data dari form
    $nmpenyakit = $_POST['nmpenyakit'];
    
    // validasi nama penyakit
    $sql = "SELECT basis_aturan.idaturan, basis_aturan.idpenyakit, penyakit.nmpenyakit
            FROM basis_aturan 
            INNER JOIN penyakit ON basis_aturan.idpenyakit=penyakit.idpenyakit 
            WHERE nmpenyakit='$nmpenyakit'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>Data basis aturan penyakit tersebut sudah ada</strong>
        </div>
        <?php
    } else {

        // Mengambil data penyakit
        $sql = "SELECT * FROM penyakit WHERE nmpenyakit='$nmpenyakit'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $idpenyakit = $row['idpenyakit'];

            // Proses simpan basis aturan
            $sql = "INSERT INTO basis_aturan VALUES (NULL, '$idpenyakit')";
            mysqli_query($conn, $sql);

            // Proses mengambil data aturan
            $sql = "SELECT * FROM basis_aturan ORDER BY idaturan DESC";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $idaturan = $row['idaturan'];

            // Proses simpan detail basis aturan
            $idgejala = $_POST['idgejala'];
            $jumlah = count($idgejala);
            for ($i = 0; $i < $jumlah; $i++) {
                $idgejalane = $idgejala[$i];
                $sql_detail = "INSERT INTO detail_basis_aturan VALUES ($idaturan, '$idgejalane')";
                mysqli_query($conn, $sql_detail);
            }

            header("Location:?page=aturan");
        } else {
            ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Nama penyakit tidak ditemukan</strong>
            </div>
            <?php
        }
    }
}
?>

<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST" name="Form" onsubmit="return validasiForm()">
            <div class="card border-dark">
                <div class="card">
                    <div class="card-header bg-primary text-white border-dark">
                        <strong>Tambah Data Basis Aturan</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="">Nama Penyakit</label>
                            <select class="form-control chosen" data-placeholder="Pilih Nama Penyakit" name="nmpenyakit">
                                <option value=""></option>
                                <?php
                                $sql = "SELECT * FROM penyakit ORDER BY nmpenyakit ASC";
                                $result = $conn->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    ?>
                                    <option value="<?php echo $row['nmpenyakit']; ?>">
                                        <?php echo $row['nmpenyakit']; ?>
                                    </option>
                                    <?php
                                }
                                ?>
                            </select>
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

                        <input class="btn btn-primary" type="submit" name="simpan" value="Simpan">
                        <a class="btn btn-danger" href="?page=aturan">Batal</a>
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
        // validasi nama penyakit
        var nmpenyakit =document.forms["Form"]["nmpenyakit"].value;

        if(nmpenyakit=="")
        {
            alert("Pilih nama penyakit");
            return false;
        }

        // validasi gejala yang belum dipilih
        var checkboxes = document.getElementsByName("idgejala[]");

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
