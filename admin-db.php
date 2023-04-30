<?php

function addGame($genre, $title, $release_date, $unit_price)
{
     global $db;
     $query = "INSERT INTO Game (genre, title, release_date, unit_price) VALUES (:genre, :title, :release_date, :unit_price);";
     $statement = $db->prepare($query);
     $statement->bindValue(':genre', $genre);
     $statement->bindValue(':title', $title);
     $statement->bindValue(':release_date', $release_date);
     $statement->bindValue(':unit_price', $unit_price);
     $statement->execute();
     $statement->closeCursor();
}

function deleteGame($game_id)
{
     global $db;
     $query = "DELETE FROM Game WHERE game_id=:game_id;";
     $statement = $db->prepare($query);
     $statement->bindValue(':game_id', $game_id);
     $statement->execute();
     $statement->closeCursor();
}

?>