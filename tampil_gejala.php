<div class="card">
  <div class="card-header bg-primary text-white border-dark"><strong>Data Gejala</strong></div>
  <div class="card-body">
<a class="btn btn-primary mb-2" href="?page=gejala&action=tambah">Tambah</a>
<table class="table table-bordered" id="myTable">
    <thead>
      <tr>
        <th width="80px">No.</th>
        <th width="700px">Nama Gejala</th>
        <th width="100px"></th>
      </tr>
    </thead>
    <tbody>
	<?php
        $no=1;
        $sql = "SELECT*FROM gejala ORDER BY nmgejala ASC";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
    ?>
        <tr>
            <td><?php echo $no++; ?></td>
            <td><?php echo $row['nmgejala']; ?></td>
            <td align="center"> 
                <a class="btn btn-warning" href="?page=gejala&action=update&id=<?php echo $row['idgejala']; ?>">
                    <i class="fas fa-edit"></i>
                </a>
                <a onclick="return confirm('Yakin menghapus data ini ?')" class="btn btn-danger" href="?page=gejala&action=hapus&id=<?php echo $row['idgejala']; ?>">
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