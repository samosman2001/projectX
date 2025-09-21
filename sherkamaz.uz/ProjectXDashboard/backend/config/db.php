<?php
try{
$pdo = new PDO("mysql:host=localhost;port=3306;dbname=projectx;charset=utf8mb4","root","",
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