<?php
session_start(); // start the session

include "../connect.php";

$error_message = '';

if(isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = sha1($_POST['password']); // hash the password input using sha1

    $query = "SELECT * FROM admin WHERE username=:username AND password=:password";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    if($stmt->rowCount() > 0) { // if user exists and password matches
        $_SESSION['admin'] = $username; // create a session variable to store the admin's username
    }
    else {
        $error_message = "Invalid username or password";
    }
}
?>



<!DOCTYPE html>
<html>
<head>
	<title>Admin | Login</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <!-- SweetAlert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
	<div class="container mt-5">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h4 class="text-center">Admin Login</h4>
					</div>
                    <h6 style="text-align: center; color: red; margin-top: 10px">NOTE AUTHORIZE ACCESS BY ADMIN ONLY</h6>
					<div class="card-body">
						<form method="POST" action="">
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" id="username" class="form-control" required>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control" required>
							</div>
							<button type="submit" class="btn btn-primary btn-block">Login</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
        <!-- SWEETALERT SCRIPT -->
    <?php if (isset($_SESSION['admin'])): ?>
        <script>
            swal("Success", "You have successfully logged in!", "success")
                .then(function() {
                    window.location.href = 'admindashboard.php';
                });
        </script>
    <?php endif ?>
    <?php if ($error_message): ?>
        <script>
            swal("Error", "<?= $error_message ?>", "error");
        </script>
    <?php endif ?>
</body>
</html>
