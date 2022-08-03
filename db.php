<?php
$dbhost = "localhost:3306";
$dbpass = "jnur1997";
$dbuser = "root";
 $dbname = "pet_store_rr" ;

$connection = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

if (mysqli_connect_errno()){
    die("DB connection failed " . mysqli_connect_errno() . " (" . mysqli_connect_errno() . ")"
    );
}