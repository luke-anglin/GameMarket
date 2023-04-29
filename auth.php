<?php session_start(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Game Market</title>
  <!-- Material UI stylesheets -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-icons/3.0.2/iconfont/material-icons.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.5.0/mdb.min.css" integrity="sha512-W+GfoOFQR1rwnr5/dkrJLD8Wz7VmyvOaJH7G9Xzs8pKq+oLHJmt00b/IC0J8pETq3BvIbT6Tl9USUfxxR2Qx6w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <!-- jQuery -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  
  <!-- Bootstrap JS -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Game Market</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link" href="catalog.php">Catalog</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="home.php">Auctions</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="cart.php">Shopping Cart</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="resells.php">Resells</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="admin.php">Admin</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="stats.php">My Stats</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="auth.php">Login/Logout</a>
      </li>
    </ul>
  </div>
</nav>

<!-- Connect to database -->

<!-- Content goes here -->

<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<h2 class="text-center">Login/Signup Form</h2>
				<ul class="nav nav-tabs">
					<li class="nav-item">
						<a class="nav-link active" data-toggle="tab" href="#login">Login</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" data-toggle="tab" href="#signup">Signup</a>
					</li>
          <?php if(isset($_SESSION['user_id'])) { ?>
                    <li class="nav-item ml-auto">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
          <?php } ?>
				</ul>
				<div class="tab-content">
					<div id="login" class="tab-pane fade show active">
						<form action="auth.php" method="POST">
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="text" class="form-control" id="username" name="username" required>
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" class="form-control" id="password" name="password" required>
							</div>
							<button type="submit" class="btn btn-primary">Login</button>
						</form>
         
					</div>
					<div id="signup" class="tab-pane fade">
						<form action="auth.php" method="POST">
							<div class="form-group">
								<label for="new_username">New Username:</label>
								<input type="text" class="form-control" id="new_username" name="new_username" required>
							</div>
							<div class="form-group">
								<label for="new_password">New Password:</label>
								<input type="password" class="form-control" id="new_password" name="new_password" required>
							</div>
                            <div class="form-group">
								<label for="first_name">First Name:</label>
								<input class="form-control" id="first_name" name="first_name" required>
							</div>
                            <div class="form-group">
								<label for="last_name">Last Name:</label>
								<input class="form-control" id="last_name" name="last_name" required>
							</div>
                            
							<button type="submit" class="btn btn-primary">Signup</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
require('connect.php');
# session_start();
if(isset($_POST['new_username']) && isset($_POST['new_password']) && isset($_POST['last_name']) && isset($_POST['first_name'])){
    $new_username = $_POST['new_username'];
    $new_password = $_POST['new_password'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $stmt = $db->prepare("SELECT * FROM User WHERE username = ?");
    $stmt->execute([$new_username]);
    $user = $stmt->fetch();
    // echo "user is " . var_dump($user);

    if(!$user){
        echo "here";

        $stmt = $db->prepare("INSERT INTO User (first_name, last_name, username, p_word) VALUES (?, ?, ?, ?)");
        $stmt->execute([$first_name, $last_name, $new_username, $new_password]);
        $_SESSION['user_id'] = $db->lastInsertId();
        echo "Signed up!";
    }else{
        echo "Username already exists. Login instead.";
    }
}
if(isset($_POST['username']) && isset($_POST['password'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM User WHERE username = ? AND p_word = ?");
    $stmt->execute([$username, $password]);
    $user = $stmt->fetch();

    if($user){
        $_SESSION['user_id'] = $user['user_id'];
        $u_id = $_SESSION['user_id'];
        echo "Logged in! Session user id is $u_id";
    }else{
        echo "Incorrect username or password.";
    }
}
?>
</body>
</html>
