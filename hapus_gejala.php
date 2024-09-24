<?php

$idgejala=$_GET['id'];

$sql = "DELETE FROM gejala WHERE idgejala='$idgejala'";
if ($conn->query($sql) === TRUE) {
    header("Location:?page=gejala");
}
$conn->close();
?>