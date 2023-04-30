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
<?php require "admin-db.php" ?>
<?php session_start() ?>

<?php
if (!isset($_SESSION['user_id'])) {
  echo "You Do Not Have Access To This Page: Please Log In";
  exit;
}
$uid = $_SESSION['user_id'];
$ad = $db->prepare("SELECT admin FROM User WHERE user_id = $uid");
$ad->execute();
$adminValue = $ad->fetch(PDO::FETCH_ASSOC);
if ($adminValue["admin"] != 1) {
  echo "You Do Not Have Access To This Page: You are not admin";
  exit;
}

?>

<!-- Add A New Game -->
<div class="container">
  <h1>Add A New Game to Catalog</h1>  

  <form name="mainForm" action="admin.php" method="post">   
  <div class="row mb-3 mx-3">
      Unit Price:
      <input type="text" class="form-control" name="unit_price" placeholder="$USD" required
      />        
  </div>  
  <div class="row mb-3 mx-3">
      Title:
      <input type="text" class="form-control" name="title" required
      />        
  </div>  
	<div class="row mb-3 mx-3">
      Release Date:
      <input type="text" class="form-control" name="release_date" placeholder="YYYY-MM-DD" required
      />        
  </div>  
  <div class="row mb-3 mx-3">
      Genre:
      <input type="text" class="form-control" name="genre" required
      />        
  </div>  
	<div class="row mb-3 mx-3">
      <input type="submit" class="btn btn-primary" name="actionBtn" value="Add Game" title="click to insert game" />        
    </div> 
  </form>     

</div>

<!-- Update Games -->
<h2>Update Available Games In Catalog</h2>

<?php 

// Check if the filter form has been submitted
if (isset($_POST['filter'])) {
    $filterValue = $_POST['filterValue'];

    // Prepare the SQL statement to retrieve filtered data from the Game table
    $stmt = $db->prepare("SELECT game_id, unit_price, title, release_date, genre FROM Game WHERE unit_price < :filterValue AND game_id;");

    // Bind the filter value parameter to the SQL statement
    $stmt->bindValue(':filterValue', $filterValue, PDO::PARAM_INT);
} else {
    // Prepare the SQL statement to retrieve data from the Game table
    $stmt = $db->prepare("SELECT game_id, unit_price, title, release_date, genre
    FROM Game
    WHERE game_id;");
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
echo '<th><a href="#" class="sort" data-sort="unit_price">Unit Price</a></th>';
echo '<th><a href="#" class="sort" data-sort="title">Title</a></th>';
echo '<th><a href="#" class="sort" data-sort="release_date">Release Date</a></th>';
echo '<th><a href="#" class="sort" data-sort="genre">Genre</a></th>';

echo '<th>Update</th>';
echo '<th>Remove</th>';
echo '</tr></thead>';
echo '<tbody>';

foreach ($results as $row) {
  echo '<tr>';
  echo '<td>' . $row['unit_price'] . '</td>';
  echo '<td>' . $row['title'] . '</td>';
  echo '<td>' . $row['release_date'] . '</td>';
  echo '<td>' . $row['genre'] . '</td>';
  
  // Update Game Button
  echo '<td><form action="update_game.php" method="POST">
  <input type="submit" name="actionBtn" value="Update" class ="btn btn-primary" />
  <input type="hidden" name="game_id" value="' . $row['game_id'] . '" />
  <input type="hidden" name="initial" value="1">
  <a href="admin.php" /a>
  </form>
  </td>';
  // Delete Game Button
  echo '<td><form action="admin.php" method="POST">
  <input type="submit" name="actionBtn" value="Delete" class ="btn btn-danger" />
  <input type="hidden" name="game_id" value="' . $row['game_id'] . '" />
  <a href="admin.php" /a>
  </form>
  </td>';
  echo '</tr>';

  echo '</tr>';

  /*
  echo '<td><button type="submit" name="delete" value="'.$row['game_id'].'" />Delete</button></td>"';
  echo '<td><form action="home.php" method="POST">
  <input type="hidden" name="game_id" value="' . $row['game_id'] . '" />
  <input type="hidden" name="title" value="' . $row['title'] . '" />
  <input type="hidden" name="title" value="' . $row['unit_price'] . '" />
  <input type="hidden" name="release_date" value="' . $row['release_date'] . '" />
  <input type="hidden" name="Genre" value="' . $row['Genre'] . '" />
  </form>
  </td>';
  echo '</tr>';

  // Update Game Pop-Up
  echo '<div class="modal fade" id="gameModal' . $row['game_id'] . '" tabindex="-1" role="dialog" aria-labelledby="gameModalLabel' . $row['game_id'] . '" aria-hidden="true">';
  echo '<div class="modal-dialog" role="document">';
  echo '<div class="modal-content">';

  echo '<div class="modal-header">';
  echo '<h5 class="modal-title" id="gameModalLabel' . $row['game_id'] . '">Update Game</h5>';
  echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
  echo '<span aria-hidden="true">&times;</span>';
  echo '</button>';
  echo '</div>';

  echo '<div class="modal-body">';
  echo '<form action="update_game.php" method="POST">';
  echo '<input type="hidden" name="game_id" value="' . $row['game_id'] . '">';
  echo '<div class="form-group">';
  echo '<label for="title">Title</label>';
  echo '<input type="text" class="form-control" name="title" value="' . $row['title'] . '">';
  echo '</div>';

  echo '<div class="form-group">';
  echo '<label for="unit_price">Unit Price</label>';
  echo '<input type="number" class="form-control" name="unit_price" value="' . $row['unit_price'] . '">';
  echo '</div>';

  echo '<div class="form-group">';
  echo '<label for="release_date">release_date</label>';
  echo '<input type="text" class="form-control" name="release_date" value="' . $row['release_date'] . '">';
  echo '</div>';

  echo '<div class="form-group">';
  echo '<label for="genre">genre</label>';
  echo '<input type="text" class="form-control" name="genre" value="' . $row['genre'] . '">';
  echo '</div>';

  echo '<button type="submit" class="btn btn-primary">Save Changes</button>';
  echo '</form>';
  echo '</div>';

  echo '<div class="modal-footer">';
  echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
  echo '</div>';

  echo '</div>';
  echo '</div>';
  */
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

// Button Functions
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add Game"))
  {
    addGame($_POST['genre'], $_POST['title'], $_POST['release_date'], $_POST['unit_price']);
  }
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete"))
  {
    deleteGame($_POST['game_id']);
    header("refresh:2");
  }
}

?>

</body>
</html>