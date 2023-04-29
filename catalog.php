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
<?php require "connect.php" ?>


<!-- SQL Queries -->
<?php require("stats-db.php"); ?>

<!-- Content goes here -->
<?php
session_start();
$u_id = $_SESSION['user_id']; # replace with global user id from login
# echo "user id is $u_id";
?>


<h2>Featured Games</h2>
<!-- Select just a few of the games in the Game table here and display their price and title -->
<?php 
$stmt = $db->prepare('SELECT unit_price, title, avg_rating FROM Game LIMIT :offset, :limit');
$stmt->bindValue(':offset', 0, PDO::PARAM_INT); // Offset of 0
$stmt->bindValue(':limit', 5, PDO::PARAM_INT); // Limit of 10 rows
$stmt->execute();

// Fetch the results as an array
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Loop through the results and do something with each row
foreach ($results as $row) {
    echo $row['title'] . ' ' . $row['unit_price'] . ' ' . $row['avg_rating'] . ' ' . '<br>';
}
?>
<br>
<h2>Search All Games in our Catalog</h2>
<h4>Click on a column header to sort by it</h4>
<!-- Show top 25 games here by default -->


<?php 
// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $filterValue = $_POST['filterValue'];

    // Prepare the SQL statement to retrieve filtered data from the Game table
    $stmt = $db->prepare("SELECT * FROM Game WHERE unit_price < :filterValue");

    // Bind the filter value parameter to the SQL statement
    $stmt->bindValue(':filterValue', $filterValue, PDO::PARAM_INT);
} else {
    // Prepare the SQL statement to retrieve data from the Game table
    $stmt = $db->prepare("SELECT * FROM Game");
}
// Execute the statement and fetch the results
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Create the HTML table using Bootstrap classes
echo '<form method="POST">';
echo '<label for="filterValue">Filter by price (below x dollars):</label>';
echo '<input type="number" name="filterValue" id="filterValue" value="'.(isset($_POST['filter']) ? $filterValue : '').'">';
echo '<button type="submit" name="filter">Filter</button>';
echo '</form>';

echo '<table id="gameTable" class="table table-striped">';
echo '<thead><tr>';
echo '<th><a href="#" class="sort" data-sort="Game ID">Game ID</a></th>';
echo '<th><a href="#" class="sort" data-sort="Genre">Genre</a></th>';
echo '<th><a href="#" class="sort" data-sort="Average rating">Average rating</a></th>';
echo '<th><a href="#" class="sort" data-sort="title">Title</a></th>';
echo '<th><a href="#" class="sort" data-sort="avg_rating">Release Date</a></th>';
echo '<th><a href="#" class="sort" data-sort="username">Unit Price</a></th>';
echo '</tr></thead>';
echo '<tbody>';

foreach ($results as $row) {
  echo '<tr>';
  echo '<td>' . $row['game_id'] . '</td>';
  echo '<td>' . $row['genre'] . '</td>';
  echo '<td>' . $row['avg_rating'] . '</td>';
  echo '<td>' . $row['title'] . '</td>';
  echo '<td>' . $row['release_date'] . '</td>';
  echo '<td>' . $row['unit_price'] . '</td>';

  echo '</tr>';
}


echo '</tbody></table>';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "AddToCart"))
  {
    // $game_id = $_POST['game_id'];
    // $title = $_POST['title'];
    // echo "<p>Running function add to cart  with $game_id and $title</p>";

    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null){
      echo "<p>Can't add game to shopping cart when you aren't logged in!</p>"; 
      exit(); 
    }

   /*
    // Part 1 - get the auction id where this game_id is sold 
    $stmt = $db->prepare("SELECT auction_id FROM Sold_on WHERE game_id = :game_id;");

    $stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);

    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    */

    $user_id = $_SESSION['user_id'];
    $auction_id = $_POST['auction_id'];
    $title = $_POST['title'];
    $seller_name = $_POST['username'];

    // Check if you are the seller
    $stmt = $db->prepare("SELECT user_id as sell FROM User WHERE username = :username;");
    $stmt->bindValue(':username', $seller_name);
    $stmt->execute();
    $seller_id = $stmt->fetchAll();
    foreach ($seller_id as $s) {
      if ($s['sell'] == $user_id){
        echo "<script>alert('You are the seller of the game, cannot be added.');</script>"; 
        exit(); 
      }
    }

    // Check if Auction is already in your shopping cart
    $stmt = $db->prepare("SELECT EXISTS 
    (SELECT 1 FROM In_shopping_cart WHERE user_id = :user_id AND auction_id = :auction_id) as e;");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':auction_id', $auction_id, PDO::PARAM_INT);
    $stmt->execute();
    $exists = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($exists as $e) {
      if ($e['e'] == 1){
        echo "<script>alert('This game is already in your shopping cart. Not re-added');</script>"; 
        exit(); 
      }
    }

    // echo "<p>Results: " . $results . "</p>";
    foreach ($results as $row) {
      // echo "<p> gonna run this with auction id " . $row['auction_id'] . "</p>";
      $stmt = $db -> prepare ("INSERT INTO In_shopping_cart (user_id, auction_id) VALUES (:user_id, :auction_id)");
      $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
      $stmt->bindValue(':auction_id', $row['auction_id'], PDO::PARAM_INT);
      $stmt->execute();
    } 
  }
  $message = "You added $title to your shopping cart.";
  echo "<script>alert('$message');</script>";
}




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
?>




</body>
</html>
