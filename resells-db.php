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

function deleteSells($auction_to_delete)
{
    global $db;
    $query = "delete from Sells where auction_id=:auction_to_delete";
    $statement = $db->prepare($query);
    $statement->bindValue(':auction_to_delete', $auction_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function deleteSoldon($auction_to_delete)
{
    global $db;
    $query = "delete from Sold_on where auction_id=:auction_to_delete";
    $statement = $db->prepare($query);
    $statement->bindValue(':auction_to_delete', $auction_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function deleteAuction($auction_to_delete)
{
    global $db;
    $query = "delete from Auctions where auction_id=:auction_to_delete";
    $statement = $db->prepare($query);
    $statement->bindValue(':auction_to_delete', $auction_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function selectAllYourSoldon($user_id)
{
     global $db;
     $query = "select * from Sold_on NATURAL JOIN Sells WHERE user_id=:user_id ORDER BY auction_id";
     $statement = $db->prepare($query);
     $statement->bindValue(':user_id', $user_id);
     $statement->execute();
     $results = $statement->fetchAll();
     $statement->closeCursor();
     return $results;
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
