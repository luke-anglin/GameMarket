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
<?php require "connect.php" ?>
<?php session_start() ?>


<!-- SQL Queries -->
<?php require("stats-db.php"); ?>

<!-- Content goes here -->
<?php
session_start();
$u_id = $_SESSION['user_id']; # replace with global user id from login
# echo "user id is $u_id";
?>

<!-- Check if Logged In -->
<?php
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null){
  echo "You Do Not Have Access To This Page: Please Log In";
  die();
}
?>

<!-- Button Actions -->
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "X"))
  {
    deleteEmail($_POST['delete_email']);
    $email = getUserEmail($u_id);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "✓"))
  {
    if (strpos($_POST['newEmail'], '@') !== false) 
    {
      addEmail($_POST['newEmail'], $u_id);
      $email = getUserEmail($u_id);
    } 
    else 
    {
      echo "Error: Email address must include '@' symbol";
    }
  }  
  else if (!empty($_POST['phoneBtn']) && ($_POST['phoneBtn'] == "X"))
  {
    deletePhone($_POST['delete_phone']);
    $phone = getUserPhone($u_id);
  }
  else if (!empty($_POST['phoneBtn']) && ($_POST['phoneBtn'] == "✓"))
  {
    $newPhone = $_POST['newPhone'];
    if(strlen($newPhone) != 10 || !is_numeric($newPhone)) {
      echo "Please enter a 10-digit phone number";
    } else {
      addPhone($newPhone, $u_id);
      $phone = getUserPhone($u_id);
    }
  }
  else if (!empty($_POST['ratingBtn']) && ($_POST['ratingBtn'] == "✓"))
  {
    if ($_POST['newRating'] > 10)
    {
      $_POST['newRating'] = 10;
    }
    else if ($_POST['newRating'] < 1)
    {
      $_POST['newRating'] = 1;
    }
    addRating($_POST['newRating'], $u_id, $_POST['game_id']);
    $phone = getRating($u_id, $_POST['game_id']);
  }
  else if (!empty($_POST['cardBtn']) && ($_POST['cardBtn'] == "X"))
  {
    deletePayment($_POST['delete_card_type'], $_POST['delete_card_number']);
    $payment = getUserPayment($u_id);
  }
  else if (!empty($_POST['cardBtn']) && ($_POST['cardBtn'] == "✓"))
  {
    addPayment($_POST['newPaymentType'], $_POST['newPaymentNumber'], $u_id);
    $payment = getUserPayment($u_id);
  }
}
?>

<!-- Top Section: Account Info -->
<div style="width:95%;border:0.5px solid #000;margin:0 auto;border-radius:10px;background-color:#D3D3D3">
  <h3 style="padding-left:18px;padding-top:5px">Account Info</h3>
  <div style="padding-left:30px;padding-bottom:5px">

    <!-- Get Account Info -->
    <?php $info = getUserInfo($u_id)?>
    <?php $email = getUserEmail($u_id)?>
    <?php $phone = getUserPhone($u_id)?>
    <?php $payment = getUserPayment($u_id)?>

    <!-- Display Account Info -->
    <?php foreach ($info as $user_info): ?>
      Username: <?php echo $user_info["username"]?> <br>
      First Name: <?php echo $user_info["first_name"]?> <br>
      Last Name: <?php echo $user_info["last_name"]?> <br>
    <?php endforeach; ?>

    <!-- Side-by-Side Table Style -->
    <style>
    .table-container {
      display: flex;
      justify-content: center;
      gap: 5%;
      padding-right:30px;
    }
    table {
      border: 1px solid black;
      border-collapse: collapse;
      width: 50%;
    }

    </style>
    <div class="table-container">
      <!-- Table for Emails -->
      <table>
        <thead>
        <tr style="background-color:#B0B0B0">
          <th>Email(s)</th>
          <th></th>       
        </tr>
        </thead>
        <?php foreach ($email as $e): ?>
          <tr>
            <td><?php echo $e['email_address']; ?></td>
            <td align="right">
              <form action="stats.php" method="post">
                <input type="submit" name="actionBtn" value="X" class="btn btn-danger" />
                <input type="hidden" name="delete_email"
                      value="<?php echo $e['email_address']; ?>" />
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <form action="stats.php" method="post">
            <td>
              <input type="text" class="form-control" name="newEmail" placeholder="Add Email" required />
            </td>
            <td align="right">
              <input type="submit" name="actionBtn" value="✓" class="btn btn-success" />
            </td>
          </form>
        </tr>
      </table>
      <!-- Table for Phone Numbers -->
      <table>
        <thead>
        <tr style="background-color:#B0B0B0">
          <th>Phone Number(s)</th>  
          <th></th>       
        </tr>
        </thead>
        <?php foreach ($phone as $p): ?>
          <tr>
            <td><?php echo $p['phone_number']; ?></td>
            <td align="right">
              <form action="stats.php" method="post">
                <input type="submit" name="phoneBtn" value="X" class="btn btn-danger" />
                <input type="hidden" name="delete_phone"
                      value="<?php echo $p['phone_number']; ?>" />
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <form action="stats.php" method="post">
            <td>
              <input type="text" class="form-control" name="newPhone" placeholder="Add Phone Number" required />
            </td>
            <td align="right">
              <input type="submit" name="phoneBtn" value="✓" class="btn btn-success" />
            </td>
          </form>
        </tr>
      </table>
      <!-- Table for Payment -->
      <table>
        <thead>
        <tr style="background-color:#B0B0B0">
          <th>Payment(s)</th>
          <th></th>  
          <th></th>     
        </tr>
        </thead>
        <?php foreach ($payment as $pt): ?>
          <tr>
            <td><?php echo $pt['card_type']; ?></td>
            <td><?php echo $pt['card_number']; ?></td>
            <td align="right">
              <form action="stats.php" method="post">
                <input type="submit" name="cardBtn" value="X" class="btn btn-danger" />
                <input type="hidden" name="delete_card_type"
                      value="<?php echo $pt['card_type']; ?>" />
                <input type="hidden" name="delete_card_number"
                      value="<?php echo $pt['card_number']; ?>" />
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <form action="stats.php" method="post">
            <td>
              <input type="text" class="form-control" name="newPaymentType" placeholder="Payment Type" required />
            </td>
            <td>
              <input type="text" class="form-control" name="newPaymentNumber" placeholder="Payment Number" required />
            </td>
            <td align="right">
              <input type="submit" name="cardBtn" value="✓" class="btn btn-success" />
            </td>
          </form>
        </tr>
      </table>

    </div>

  </div>
</div>

<!-- Left Section: Purchase History -->
<div style="width:95%;float:left;background:#D3D3D3;border:0.5px solid #000;border-radius:10px;margin-left:2.5%;">
  <h4 style="padding-left:18px;padding-top:5px">Purchase History</h4>
  <div style="padding-left:30px;padding-bottom:5px">

    <!-- Get Account Info -->
    <?php $purchase = getPurchases($u_id)?>

    <!-- Display Account Info -->
    <table class="purchase-history">
    <style>
      .purchase-history {
        border-collapse: collapse;
        width: 100%;
        table-layout: fixed;
      }

      .purchase-history th, .purchase-history td {
        text-align: left;
        padding: 8px;
      }

      .purchase-history th:nth-child(1), .purchase-history td:nth-child(1) {
        width: 20%;
      }

      .purchase-history th:nth-child(2), .purchase-history td:nth-child(2) {
        width: 25%;
      }

      .purchase-history th:nth-child(3), .purchase-history td:nth-child(3) {
        width: 20%;
      }

      .purchase-history th:nth-child(4), .purchase-history td:nth-child(4) {
        width: 15%;
      }

      .purchase-history th:nth-child(5), .purchase-history td:nth-child(5) {
        width: 20%;
      }
    </style>

    <thead>
      <tr style="background-color:#B0B0B0">
        <th>Purchase Date</th>
        <th>Game</th>
        <th>Seller</th>
        <th>Rating</th>
        <th>Edit Rating (1-10)</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($purchase as $item): ?>
        <tr>
          <td><?php echo $item["purchase_date"] ?></td>
          <?php $gameTitle = getGameTitle($item["game_id"])?>
          <td><?php echo $gameTitle["title"] ?></td>
          <?php $username = getUsername($item["seller_id"])?>
          <td><?php echo $username["username"] ?></td>
          <?php $purchase = getRating($u_id, $item["game_id"])?>
          <td><?php echo $purchase["rating"]?></td>
          <td>
            <form action="stats.php" method="post">
              <div class="form-group" style="display:flex">
                <input type="text" class="form-control" name="newRating" placeholder="Add Rating" required />
                <input type="hidden" name="game_id" value="<?php echo $item['game_id']; ?>" />
                <button type="submit" name="ratingBtn" value="✓" class="btn btn-success" style="margin-left:10px">✓</button>
              </div>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
    </table>

  </div>
</div>


</body>
</html>
