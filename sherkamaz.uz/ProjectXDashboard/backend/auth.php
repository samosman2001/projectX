<?php 
//Protect Pages Like main.php
session_start();
if(!isset($_SESSION['user_id']))
{
	echo "test";
	header("Location: /ProgrammingStuff/ProjectXDashboard/frontend/exit.php");
}

 ?>