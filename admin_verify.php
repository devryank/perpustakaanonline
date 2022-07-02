<?php
	session_start();
	if(!isset($_POST['submit'])){
		echo "Nama pengguna atau Kata Sandi salah, silahkan periksa ulang!";
		exit;
	}
	require_once "./functions/database_functions.php";
	$conn = db_connect();

	$name = trim($_POST['name']);
	$pass = trim($_POST['pass']);

	if($name == "" || $pass == ""){
		echo "Nama pengguna atau Kata Sandi kosong!";
		exit;
	}

	$name = mysqli_real_escape_string($conn, $name);
	$pass = mysqli_real_escape_string($conn, $pass);
	$pass = sha1($pass);

	// get from db
	$query = "SELECT name, pass from admin";
	$result = mysqli_query($conn, $query);
	if(!$result){
		echo "Empty data " . mysqli_error($conn);
		exit;
	}
	$row = mysqli_fetch_assoc($result);

	if($name != $row['name'] && $pass != $row['pass']){
		echo "Nama pengguna atau Kata Sandi salah, silahkan periksa ulang!";
		$_SESSION['admin'] = false;
		exit;
	}

	if(isset($conn)) {mysqli_close($conn);}
	$_SESSION['admin'] = true;
	header("Location: admin_book.php");
?>