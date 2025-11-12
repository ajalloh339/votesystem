<?php
session_start();
include 'includes/conn.php';

// Role-aware login: expects POST 'role' => 'admin'|'voter' and corresponding credentials
if(isset($_POST['login'])){
	$role = isset($_POST['role']) ? $_POST['role'] : '';
	$password = isset($_POST['password']) ? $_POST['password'] : '';

	if($role === 'admin'){
		$user = isset($_POST['username']) ? $conn->real_escape_string($_POST['username']) : '';
		if(empty($user) || empty($password)){
			$_SESSION['error'] = 'Input credentials first';
			header('location: admin_login.php');
			exit;
		}
		$sql = "SELECT * FROM admin WHERE username = '$user'";
		$query = $conn->query($sql);
		if($query && $query->num_rows > 0){
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['admin'] = $row['id'];
				header('location: admin_index.php');
				exit;
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
				header('location: admin_login.php');
				exit;
			}
		}
		else{
			$_SESSION['error'] = 'Cannot find admin account';
			header('location: admin_login.php');
			exit;
		}
	}
	elseif($role === 'voter'){
		$user = isset($_POST['voter']) ? $conn->real_escape_string($_POST['voter']) : '';
		if(empty($user) || empty($password)){
			$_SESSION['error'] = 'Input credentials first';
			header('location: voter_login.php');
			exit;
		}
		$sql = "SELECT * FROM voters WHERE voters_id = '$user'";
		$query = $conn->query($sql);
		if($query && $query->num_rows > 0){
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['voter'] = $row['id'];
				header('location: home.php');
				exit;
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
				header('location: voter_login.php');
				exit;
			}
		}
		else{
			$_SESSION['error'] = 'Cannot find voter account';
			header('location: voter_login.php');
			exit;
		}
	}
	else{
		// Fallback: old behavior (try admin then voter)
		$user = '';
		if(isset($_POST['username'])){
			$user = $conn->real_escape_string($_POST['username']);
		}
		elseif(isset($_POST['voter'])){
			$user = $conn->real_escape_string($_POST['voter']);
		}
		if(empty($user) || empty($password)){
			$_SESSION['error'] = 'Input credentials first';
			header('location: index.php');
			exit;
		}
		// Try admin
		$sql = "SELECT * FROM admin WHERE username = '$user'";
		$query = $conn->query($sql);
		if($query && $query->num_rows > 0){
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['admin'] = $row['id'];
				header('location: admin_index.php');
				exit;
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
				header('location: index.php');
				exit;
			}
		}
		// Try voters
		$sql = "SELECT * FROM voters WHERE voters_id = '$user'";
		$query = $conn->query($sql);
		if($query && $query->num_rows > 0){
			$row = $query->fetch_assoc();
			if(password_verify($password, $row['password'])){
				$_SESSION['voter'] = $row['id'];
				header('location: home.php');
				exit;
			}
			else{
				$_SESSION['error'] = 'Incorrect password';
				header('location: index.php');
				exit;
			}
		}
		$_SESSION['error'] = 'Cannot find account with those credentials';
		header('location: index.php');
		exit;
	}
}
else{
	$_SESSION['error'] = 'Input credentials first';
	header('location: index.php');
	exit;
}
?>