<?php
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['initial'] == "1") {
        $game_id = $_POST['game_id'];
        $stmt = $db -> prepare ("SELECT * FROM Game WHERE game_id = $game_id");
        $genre = null; 
        $title = null; 
        $unit_price = null; 
        $stmt -> execute();
        $row = $stmt -> fetchAll(PDO::FETCH_ASSOC); 
        $stmt -> closeCursor();
        $genre = $row[0]['genre'];
        $title = $row[0]['title'];
        $unit_price = $row[0]['unit_price'];
    } else {
        // TODO: PDO SQL query to update row in Game table with form data
        $stmt = $db->prepare("UPDATE Game SET title = :title, unit_price = :unit_price, genre = :genre WHERE game_id = :game_id");
        $stmt->bindParam(':title', $_POST['title']);
        $stmt->bindParam(':unit_price', $_POST['unit_price']);
        $stmt->bindParam(':genre', $_POST['genre']);
        $stmt->bindParam(':game_id', $_POST['game_id']);
        $stmt->execute();
        $stmt -> closeCursor();
        // $message = "Updating table, now the title is " . $_POST['title'] . " and the unit price is " . $_POST['unit_price'] . " and the genre is " . $_POST['genre'] . ". You will be redirected back in 8 seconds."; 
        // echo "<script>alert('$message');</script>";
        header("Location:admin.php");
        exit;
    }
}
?>
<!-- Include necessary Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
  crossorigin="anonymous">
<!-- Start form with Bootstrap classes -->
<form class="form-horizontal" action="update_game.php" method="POST">
  <div class="form-group">
    <label for="title" class="col-sm-2 control-label">Title</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="unit_price" class="col-sm-2 control-label">Unit Price</label>
    <div class="col-sm-10">
      <input type="number" class="form-control" id="unit_price" name="unit_price" value="<?php echo $unit_price; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="genre" class="col-sm-2 control-label">Genre</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="genre" name="genre" value="<?php echo $genre; ?>">
    </div>
  </div>
  <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
  <input type="hidden" name="initial" value="0">
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </div>
</form>
<!-- Include necessary Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
  integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
  crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
  integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
  integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
  crossorigin="anonymous"></script>
