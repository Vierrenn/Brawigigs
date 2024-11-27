<?php

$host="localhost";
$user="root";
$pass="";
$db="brawigigs";
$conn=new mysqli($host, $user, $pass, $db);

if($conn->connect_error){
    echo "Gagal tersambung ke database".$conn->connect_error;
}
?>

