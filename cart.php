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
<?php require "connect.php" ?>
<?php require("cart-db.php"); ?>

<?php session_start(); $uid = $_SESSION['user_id']; ?>

<?php if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null){
  echo "<p>Can't have a shopping cart without being logged in</p>"; 
  die();
} ?>


<!-- Content goes here -->

<h2>Search All Games In Shopping Cart</h2>
<h4>Click on a column header to sort by it</h4>
<!-- Show top 25 games here by default -->
<?php 
// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $filterValue = $_POST['filterValue'];

    // Prepare the SQL statement to retrieve filtered data from the Game table
    $stmt = $db->prepare("SELECT q1.*,q2.username
    FROM (SELECT auction_id, game_id, price, title, avg_rating, stock FROM 
          In_shopping_cart NATURAL LEFT JOIN Sold_on NATURAL LEFT JOIN Game NATURAL LEFT JOIN Auctions
         WHERE user_id=$uid) AS q1,
         (SELECT auction_id, username
         FROM Sells NATURAL JOIN User
        WHERE auction_id IN (SELECT auction_id FROM In_shopping_cart WHERE user_id = $uid)) AS q2
    WHERE q1.auction_id=q2.auction_id AND price < :filterValue AND game_id IN (SELECT game_id FROM Sold_on);");

    // Bind the filter value parameter to the SQL statement
    $stmt->bindValue(':filterValue', $filterValue, PDO::PARAM_INT);
} else {
    // Prepare the SQL statement to retrieve data from the Game table
    $stmt = $db->prepare("SELECT q1.*,q2.username
    FROM (SELECT auction_id, game_id, price, title, avg_rating, stock FROM 
          In_shopping_cart NATURAL LEFT JOIN Sold_on NATURAL LEFT JOIN Game NATURAL LEFT JOIN Auctions
         WHERE user_id=$uid) AS q1,
    	(SELECT auction_id, username
         FROM Sells NATURAL JOIN User
        WHERE auction_id IN (SELECT auction_id FROM In_shopping_cart WHERE user_id=$uid)) AS q2
    WHERE q1.auction_id=q2.auction_id;");
}

// Execute the statement and fetch the results
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt->closeCursor();

// Create the HTML table using Bootstrap classes
echo '<form method="POST">';
echo '<label for="filterValue">Filter by price (below x dollars):</label>';
echo '<input type="number" name="filterValue" id="filterValue" value="'.(isset($_POST['filter']) ? $filterValue : '').'">';
echo '<button type="submit" name="filter">Filter</button>';
echo '</form>';

echo '<table id="gameTable" class="table table-striped">';
echo '<thead><tr>';
echo '<th><a href="#" class="sort" data-sort="auction_id">Auction #</a></th>';
echo '<th><a href="#" class="sort" data-sort="price">Price (USD)</a></th>';
echo '<th><a href="#" class="sort" data-sort="stock">Stock</a></th>';
echo '<th><a href="#" class="sort" data-sort="title">Title</a></th>';
echo '<th><a href="#" class="sort" data-sort="avg_rating">Average Rating</a></th>';
echo '<th><a href="#" class="sort" data-sort="username">Seller</a></th>';
echo '<th><a href="#">Remove</a></th>';
echo '</tr></thead>';
echo '<tbody>';

foreach ($results as $row) {
    echo '<tr>';
    echo '<td>' . $row['auction_id'] . '</td>';
    echo '<td>' . $row['price'] . '</td>';
    echo '<td>' . $row['stock'] . '</td>';
    echo '<td>' . $row['title'] . '</td>';
    echo '<td>' . $row['avg_rating'] . '</td>';
    echo '<td>' . $row['username'] . '</td>';
    echo '<td><form action="cart.php" method="POST">
    <input type="submit" name="actionBtn" value="Delete" class ="btn btn-danger" />
    <input type="hidden" name="auction_id" value="' . $row['auction_id'] . '" />
    </form>
    </td>';
    echo '</tr>';
}

echo '</tbody></table>';

// Include DataTables jQuery plugin and initialize the table
echo '<script src="//cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>';
echo '<script>
$(document).ready(function() {
    $("#gameTable").DataTable({
        "order": []
    });
    
    // Add click event handlers for the column headers to sort by the clicked column
    $(".sort").on("click", function() {
        var column = $(this).data("sort");
        $("#gameTable").DataTable().order([column, "asc"]).draw();
    });
});
</script>';

echo '<form method="POST">
<button class="btn btn-primary" type="submit"name="actionBtn" value="Buy">Buy All Games</button>
</form>';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete"))
  {
    $auction_id = $_POST['auction_id'];
    // $uid is the userid
    deletefromCart($uid, $auction_id);
    header("refresh:2");
  }
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Buy"))
  {
    // Check if user has a payment method on their account
    $pay = checkPayment($uid);
    foreach ($pay as $p) {
      if ($p['p'] < 1)
      {
        echo "<script>alert('You have no PAYMENT METHOD on record. Please add one on stats page.');</script>";
        exit();
      }
    }

    // Buys all games in the shopping cart
    // "utilizes top card on top of list"
    buyGamesInCart();
    header("refresh:2");

  }
}

?>

<!-- 
<form method="post">
    <button class="btn btn-primary" type="submit"name="actionBtn" value="Buy">Buy games</button>
</form>
-->

</body>
</html>