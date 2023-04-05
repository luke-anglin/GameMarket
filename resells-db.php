<?php
function addAuction($auction_id, $price, $stock)
{
    global $db;
    $query = "insert into Auctions values (:auction_id, :price, :stock)";
    $statement = $db->prepare($query);
    $statement->bindValue(':auction_id', $auction_id);
    $statement->bindValue(':price', $price);
    $statement->bindValue(':stock', $stock);
    $statement->execute();
    $statement->closeCursor();
}

function selectGameID($game)
{
     global $db;
     $query = "select game_id from Game where title=:game";
     $statement = $db->prepare($query);
     $statement->bindValue(':game', $game);
     $statement->execute();
     $results = $statement->fetchColumn();
     $statement->closeCursor();
     return $results;
}

function getCountAuctions()
{
     global $db;
     $query = "select COUNT(*) from Auctions";
     $statement = $db->prepare($query);
     $statement->execute();
     $results = $statement->fetchColumn();
     $statement->closeCursor();
     return $results;
}

function addSells($user_id, $auction_id)
{
    global $db;
    $query = "insert into Sells values (:user_id, :auction_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':auction_id', $auction_id);
    $statement->execute();
    $statement->closeCursor();
}

function addSoldon($auction_id, $game_id)
{
    global $db;
    $query = "insert into Sold_on values (:auction_id, :game_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':auction_id', $auction_id);
    $statement->bindValue(':game_id', $game_id);
    $statement->execute();
    $statement->closeCursor();
}

function selectAllAuctions()
{
     global $db;
     $query = "select * from Auctions";
     $statement = $db->prepare($query);
     $statement->execute();
     $results = $statement->fetchAll();
     $statement->closeCursor();
     return $results;
}

?>