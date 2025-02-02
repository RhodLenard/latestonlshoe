<?php
session_start();
if (isset($_SESSION['admin_id'])) {
	include('admin_home.php');
} else {
	include("admin_index.php");
}

if (!isset($_SESSION['admin_id']) || (trim($_SESSION['admin_id']) == '')) {
	header("location:admin_index.php");
	exit();
}
