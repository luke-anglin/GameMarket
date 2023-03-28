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
    </ul>
  </div>
</nav>

<!-- Connect to database -->
<?php require "connect.php" ?>
<!-- Content goes here -->

<script>
  connect();
</script>
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
<h2>Search All Games</h2>
<h4>Click on a column header to sort by it</h4>
<!-- Show top 25 games here by default -->
<?php 
// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $filterValue = $_POST['filterValue'];

    // Prepare the SQL statement to retrieve filtered data from the Game table
    $stmt = $db->prepare("SELECT unit_price, title, avg_rating FROM Game WHERE unit_price < :filterValue");

    // Bind the filter value parameter to the SQL statement
    $stmt->bindValue(':filterValue', $filterValue, PDO::PARAM_INT);
} else {
    // Prepare the SQL statement to retrieve data from the Game table
    $stmt = $db->prepare("SELECT unit_price, title, avg_rating FROM Game");
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
echo '<th><a href="#" class="sort" data-sort="unit_price">Unit Price</a></th>';
echo '<th><a href="#" class="sort" data-sort="title">Title</a></th>';
echo '<th><a href="#" class="sort" data-sort="avg_rating">Average Rating</a></th>';
echo '<th><a href="#">Cart</a></th>';
echo '</tr></thead>';
echo '<tbody>';

foreach ($results as $row) {
  echo '<tr>';
  echo '<td>' . $row['unit_price'] . '</td>';
  echo '<td>' . $row['title'] . '</td>';
  echo '<td>' . $row['avg_rating'] . '</td>';
  echo '<td><button type="button" class="btn btn-success" onclick="addToCart()">Add to Cart</button>
  </td>';
  echo '</tr>';
}


echo '</tbody></table>';

function addToCart() {
  
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
