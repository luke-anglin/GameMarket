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
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">  
    
  <title>Bootstrap example</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />

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

<?php
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "Please log in to continue.";
    exit;
}

require("resells-db.php");
$user_id = $_SESSION['user_id'];
$auction_id = getCountAuctions() + 1;
$auction_info_to_update = null;
$auctions = selectAllAuctions();
$soldon = selectAllYourSoldon($user_id);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add Auction"))
  {
    addAuction($auction_id, $_POST['price'], $_POST['stock']);
    $game_id = selectGameID($_POST['game']);
    addSells($user_id, $auction_id);
    addSoldon($auction_id, $game_id);
    $soldon = selectAllYourSoldon($user_id);
  }
  else if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete"))
  {
      deleteSells($_POST['auction_to_delete']);
      deleteSoldon($_POST['auction_to_delete']);
      deleteAuction($_POST['auction_to_delete']);
      $soldon = selectAllYourSoldon($user_id);
  }
}
?>

<div class="container">
  <h1>Create Your Stock</h1>  

  <form name="mainForm" action="resells.php" method="post">   
  <div class="row mb-3 mx-3">
      Price:
      <input type="text" class="form-control" name="price" required 
          value="<?php if ($auction_info_to_update!=null) echo $auction_info_to_update['price'];?>"
      />        
    </div>  
    <div class="row mb-3 mx-3">
      Stock:
      <input type="text" class="form-control" name="stock" required 
          value="<?php if ($auction_info_to_update!=null) echo $auction_info_to_update['stock'];?>"
      />        
    </div>  
	<div class="row mb-3 mx-3">
      Game:
      <input type="text" class="form-control" name="game" required 
          value="<?php if ($auction_info_to_update!=null) echo $auction_info_to_update['game'];?>"
      />        
    </div>  
	<div class="row mb-3 mx-3">
      <input type="submit" class="btn btn-primary" name="actionBtn" value="Add Auction" title="click to insert auction" />        
    </div> 
  </form>     

  <div class="row justify-content-center">  
    <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
      <thead>
      <tr style="background-color:#B0B0B0">
        <th>Auction ID</th>   
        <th>Game ID</th>    
        <th>Delete?</th>
      </tr>
      </thead>
    <?php foreach ($soldon as $item): ?>
      <tr>
        <td><?php echo $item['auction_id']; ?></td>
        <td><?php echo $item['game_id']; ?></td>  
        <td>
          <form action="resells.php" method="post">
            <input type="submit" name="actionBtn" value="Delete" class ="btn btn-danger" />
            <input type="hidden" name="auction_to_delete"
                   value="<?php echo $item['auction_id']; ?>" />
         </form>
        </td>     
      </tr>
    <?php endforeach; ?>
    </table>
  </div>

</div>

</body>
</html>
