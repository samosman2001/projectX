<?php
try{
$pdo = new PDO("mysql:host=localhost;dbname=user14835_projectx;charset=utf8mb4","user14835_projectx","ProjectX",
	[
     PDO::ATTR_ERRMODE =>PDO::ERRMODE_EXCEPTION,
     //for the better practice to use FETCH_ASSOC,
     PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	]);
}
catch(PDOException $e)
{
	die("Database Error :" . $e->getMessage());
}
?>