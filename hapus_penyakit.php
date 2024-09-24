<?php

$idpenyakit=$_GET['id'];

$sql = "DELETE FROM penyakit WHERE idpenyakit='$idpenyakit'";
if ($conn->query($sql) === TRUE) {
    header("Location:?page=penyakit");
}
$conn->close();
?>