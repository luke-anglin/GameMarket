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
        $genre = $row[0]['genre'];
        $title = $row[0]['title'];
        $unit_price = $row[0]['unit_price'];
    } else {
 // TODO: PDO SQL query to update row in Game table with form data
 $stmt = $db->prepare("UPDATE Game SET title = :title, unit_price = :unit_price, genre = :genre WHERE game_id = :game_id");
echo "Updating table, now the title is" . $_POST['title'] . " and the unit price is " . $_POST['unit_price'] . " and the genre is " . $_POST['genre'] . "<br>"; 
 $stmt->bindParam(':title', $_POST['title']);
 $stmt->bindParam(':unit_price', $_POST['unit_price']);
 $stmt->bindParam(':genre', $_POST['genre']);
 $stmt->bindParam(':game_id', $_POST['game_id']);
 $stmt->execute();
    }
    
}
?>
<form action="update_game.php" method="POST">
  <div class="form-group">
    <label for="title">Title</label>
    <input type="text" class="form-control" id="title" name="title" value="<?php echo $title; ?>">
  </div>
  <div class="form-group">
    <label for="unit_price">Unit Price</label>
    <input type="number" class="form-control" id="unit_price" name="unit_price" value="<?php echo $unit_price; ?>">
  </div>
  <div class="form-group">
    <label for="genre">Genre</label>
    <input type="text" class="form-control" id="genre" name="genre" value="<?php echo $genre; ?>">
  </div>
  <input type="hidden" name="game_id" value="<?php echo $game_id; ?>">
  <input type="hidden" name="initial" value="0">
  <button type="submit" class="btn btn-primary">Submit</button>
</form>
