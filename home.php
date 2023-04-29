<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Game Market</title>
  
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  
  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js" integrity="sha512-pumBsjNRGGqkPzKHndZMaAG+bir374sORyzM3uulLV14lN5LyykqNk8eEeUlUkB3U0M4FApyaHraT65ihJhDpQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>  
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
      <li class="nav-item">
        <a class="nav-link" href="auth.php">Login/Logout</a>
      </li>
    </ul>
  </div>
</nav>
<!-- Connect to database -->
<?php require "connect.php" ?>
<?php session_start() ?>
<!-- Content goes here -->

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
<br>
<br>
<h2>Search All Games (that are being Auctioned)</h2>
<h4>Click on a column header to sort by it</h4>
<!-- Show top 25 games here by default -->


<?php 
// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $filterValue = $_POST['filterValue'];

    // Prepare the SQL statement to retrieve filtered data from the Game table
    $stmt = $db->prepare("SELECT auction_id, game_id, price, title, avg_rating, username, stock
    FROM Game NATURAL RIGHT JOIN Sold_on NATURAL LEFT JOIN Auctions NATURAL LEFT JOIN Sells NATURAL LEFT JOIN User WHERE unit_price < :filterValue AND game_id IN (SELECT game_id FROM Sold_on);");

    // Bind the filter value parameter to the SQL statement
    $stmt->bindValue(':filterValue', $filterValue, PDO::PARAM_INT);
} else {
    // Prepare the SQL statement to retrieve data from the Game table
    $stmt = $db->prepare("SELECT auction_id, game_id, price, title, avg_rating, username, stock
    FROM Game NATURAL RIGHT JOIN Sold_on NATURAL LEFT JOIN Auctions NATURAL LEFT JOIN Sells NATURAL LEFT JOIN User
    WHERE game_id IN (SELECT game_id FROM Sold_on);");
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
echo '<th><a href="#" class="sort" data-sort="unit_price">Price (USD)</a></th>';
echo '<th><a href="#" class="sort" data-sort="stock">Stock</a></th>';
echo '<th><a href="#" class="sort" data-sort="title">Title</a></th>';
echo '<th><a href="#" class="sort" data-sort="avg_rating">Average Rating</a></th>';
echo '<th><a href="#" class="sort" data-sort="username">Seller</a></th>';
echo '<th><a href="#">Cart</a></th>';
echo '</tr></thead>';
echo '<tbody>';

foreach ($results as $row) {
  echo '<tr>';
  echo '<td>' . $row['price'] . '</td>';
  echo '<td>' . $row['stock'] . '</td>';
  echo '<td>' . $row['title'] . '</td>';
  echo '<td>' . $row['avg_rating'] . '</td>';
  echo '<td>' . $row['username'] . '</td>';
  echo '<td><form action="home.php" method="POST">
  <input type="submit" name="actionBtn" value="AddToCart" class ="btn btn-danger" />
  <input type="hidden" name="game_id" value="' . $row['game_id'] . '" />
  <input type="hidden" name="title" value="' . $row['title'] . '" />
  </form>
  </td>';
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

    // Check if Auction is already in your shopping cart
    if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null){
      echo "<p>This game is already in your shopping cart.</p>"; 
      exit(); 
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
