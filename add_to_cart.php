<?php
if (isset($_POST['unit_price']) && isset($_POST['avg_rating']) && isset($_POST['title'])) {
  $unit_price = $_POST['unit_price'];
  $title = $_POST['title'];
  $avg_rating = $_POST['avg_rating'];

  // TODO - change static user id to dynamic  
  $stmt = $db -> prepare ('SELECT DISTINCT game_id FROM Game WHERE unit_price = :unit_price AND title = :title AND avg_rating = :avg_rating');
  $stmt->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
  $stmt->bindValue(':avg_rating', $avg_rating, PDO::PARAM_STR);
  $stmt->bindValue(':title', $title, PDO::PARAM_STR);
  $stmt->execute();
  $game_id = $stmt->fetchAll(PDO::FETCH_ASSOC);
  echo "<h2>Game id</h2>";
  echo "unit price $unit_price" ;
  echo $_POST['title'];
  echo $_POST['avg_rating'];
  echo '<pre>'; var_dump($game_id); echo '</pre>';
}
?>