<?php 
// mengambil id dari parameter
$idusers=$_GET['id'];

// proses update
if(isset($_POST['update'])){

    // mengambil data dari form
    $role=$_POST['role'];

    $sql = "UPDATE users SET role='$role' WHERE idusers='$idusers'";
    if ($conn->query($sql) === TRUE) {
        header("Location:?page=users");
    }
}


$sql = "SELECT * FROM users WHERE idusers='$idusers'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
?>

<div class="row">
    <div class="col-sm-12">
        <form action="" method="POST">
                <div class="card border-dark">
                <div class="card">
                <div class="card-header bg-primary text-white border-dark"><strong>Update Data Users</strong></div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Username</label>
                        <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="ket" value="" readonly>
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select class="form-control chosen" data-placeholder="Pilih Role" name="role">
                            <option value="<?php echo $row['role'];?>"><?php echo $row['role'];?></option>
                            <option value="Admin">Admin</option>
                            <option value="Pasien">Pasien</option>
                        </select>                    </div>
                    <input class="btn btn-primary" type="submit" name="update" value="Update">
                    <a class="btn btn-danger" href="?page=users">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>