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
        <a class="nav-link" href="home.php">Home</a>
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
    </ul>
  </div>
</nav>

<!-- Connect to database -->
<?php require "connect.php" ?>

<!-- SQL Queries -->
<?php require("stats-db.php"); ?>

<!-- Content goes here -->
<?php
$u_id = 1; # replace with global user id from login
?>

<div style="width:95%;border:0.5px solid #000;margin:0 auto;border-radius:10px;background-color:#D3D3D3">
  <h3 style="padding-left:18px;padding-top:5px">Account Info</h3>
  <div style="padding-left:30px;padding-bottom:5px">

    <?php $info = getUserInfo($u_id)?>
    <?php $email = getUserEmail($u_id)?>
    <?php $phone = getUserPhone($u_id)?>

    <?php foreach ($info as $user_info): ?>
      Username: <?php echo $user_info["username"]?> <br>
      First Name: <?php echo $user_info["first_name"]?> <br>
      Last Name: <?php echo $user_info["last_name"]?> <br>
    <?php endforeach; ?>

    <style>
    .table-container {
      display: flex;
      justify-content: center;
      gap: 10px;
    }
    table {
      border: 1px solid black;
      border-collapse: collapse;
      width: 30%;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: center;
    }
    </style>
    <div class="table-container">
      <table>
        <thead>
        <tr style="background-color:#B0B0B0">
          <th>Email(s)</th>        
        </tr>
        </thead>
        <?php foreach ($email as $e): ?>
          <tr>
            <td><?php echo $e['email_address']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>

      <table>
        <thead>
        <tr style="background-color:#B0B0B0">
          <th>Phone Number(s)</th>        
        </tr>
        </thead>
        <?php foreach ($phone as $p): ?>
          <tr>
            <td><?php echo $p['phone_number']; ?></td>
          </tr>
        <?php endforeach; ?>
      </table>

    </div>

  </div>
</div>

<div style="width:47.5%;float:left;background:#D3D3D3;border:0.5px solid #000;border-radius:10px;margin-left:2.5%;">
  <h4 style="padding-left:18px;padding-top:5px">Purchase History</h4>
</div>

<div style="width:47.5%;float:right;background:#D3D3D3;border:0.5px solid #000;border-radius:10px;margin-right:2.5%;">
  <h4 style="padding-left:18px;padding-top:5px">Market Stats</h4>
</div>

</body>
</html>