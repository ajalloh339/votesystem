<?php
  	session_start();
  	// If already logged in, redirect to the appropriate dashboard
  	if(isset($_SESSION['admin'])){
    	header('location: admin_home.php');
  	}

    if(isset($_SESSION['voter'])){
      header('location: home.php');
    }
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<b>Voting System</b>
  	</div>

  	<div class="login-box-body text-center">
    	<p class="login-box-msg">Choose how you want to sign in</p>
    	<div class="row">
    		<div class="col-xs-6">
    			<a href="admin_login.php" class="btn btn-primary btn-block btn-flat">Login as Admin</a>
    		</div>
    		<div class="col-xs-6">
    			<a href="voter_login.php" class="btn btn-success btn-block btn-flat">Login as Voter</a>
    		</div>
    	</div>
  	</div>
  	<?php
 		if(isset($_SESSION['error'])){
 			echo "
 				<div class='callout callout-danger text-center mt20'>
 					<p>".$_SESSION['error']."</p> 
 				</div>
 			";
 			unset($_SESSION['error']);
 		}
 	?>
</div>
 	
<?php include 'includes/scripts.php' ?>
</body>
</html>