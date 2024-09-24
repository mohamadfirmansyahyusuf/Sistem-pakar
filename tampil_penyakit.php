<div class="card">
  <div class="card-header bg-primary text-white border-dark"><strong>Data Penyakit</strong></div>
  <div class="card-body">
<a class="btn btn-primary mb-2" href="?page=penyakit&action=tambah">Tambah</a>
<table class="table table-bordered" id="myTable">
    <thead>
      <tr>
        <th width="550px">No.</th>
        <th width="200px">Nama Penyakit</th>
        <th width="500px">Keterangan</th>
        <th width="200px">Solusi</th>
        <th width="100px"></th>
      </tr>
    </thead>
    <tbody>
	<?php
        $no=1;
        $sql = "SELECT*FROM penyakit ORDER BY nmpenyakit ASC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nmpenyakit']; ?></td>
            <td><?php echo $row['keterangan']; ?></td>
            <td><?php echo $row['solusi']; ?></td>
            <td align="center"> 
                <a class="btn btn-warning" href="?page=penyakit&action=update&id=<?php echo $row['idpenyakit']; ?>">
                    <i class="fas fa-edit"></i>
                </a>
                <a onclick="return confirm('Yakin menghapus data ini ?')" class="btn btn-danger" href="?page=penyakit&action=hapus&id=<?php echo $row['idpenyakit']; ?>">
                    <i class="fas fa-window-close"></i>
                </a>
            </td>
        </tr>
    <?php
     }
     $conn->close();
 ?>
   </tbody>
</table>
</div>
</div>