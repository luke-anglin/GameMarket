<?php

function getUserInfo($user_id)
{
    global $db;
    $query = "select * from User where user_id=:user_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserEmail($user_id)
{
    global $db;
    $query = "select email_address from UserEmail where user_id=:user_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function getUserPhone($user_id)
{
    global $db;
    $query = "select phone_number from UserPhone where user_id=:user_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function deleteEmail($email_to_delete)
{
    global $db;
    $query = "delete from UserEmail where email_address=:email_to_delete"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':email_to_delete', $email_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function addEmail($email, $id)
{
    global $db;
    $query = "insert into UserEmail (email_address, user_id) values (:email, :id)"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function deletePhone($phone_to_delete)
{
    global $db;
    $query = "delete from UserPhone where phone_number=:phone_to_delete"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':phone_to_delete', $phone_to_delete);
    $statement->execute();
    $statement->closeCursor();
}

function addPhone($phone, $id)
{
    global $db;
    $query = "insert into UserPhone (phone_number, user_id) values (:phone, :id)"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':phone', $phone);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $statement->closeCursor();
}

function getPurchases($id)
{
    global $db;
    $current_uid = $_SESSION['user_id'];
    $query = "SELECT purchase_date, game_id, seller_id FROM Purchases WHERE user_id = :id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function addRating($rating, $u_id, $game_id)
{
    global $db;
    $query = "SELECT COUNT(*) as count FROM Reviews WHERE user_id = :u_id AND game_id = :game_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':u_id', $u_id);
    $statement->bindValue(':game_id', $game_id);
    $statement->execute();
    $result = $statement->fetch();
    $count = $result['count'];
    $statement->closeCursor();

    if ($count > 0) {
        $query = "UPDATE Reviews SET rating = :rating WHERE user_id = :u_id AND game_id = :game_id"; 
    } else {
        $query = "INSERT INTO Reviews (user_id, game_id, rating) VALUES (:u_id, :game_id, :rating)"; 
    }

    $statement = $db->prepare($query);
    $statement->bindValue(':u_id', $u_id);
    $statement->bindValue(':game_id', $game_id);
    $statement->bindValue(':rating', $rating);
    $statement->execute();
    $statement->closeCursor();
}


function getRating($id, $game_id)
{
    global $db;
    $current_uid = $_SESSION['user_id'];
    $query = "SELECT rating FROM Reviews WHERE user_id = :id AND game_id = :game_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->bindValue(':game_id', $game_id);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function getUsername($seller_id)
{
    global $db;
    $query = "SELECT username FROM User WHERE user_id=:seller_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':seller_id', $seller_id);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

function getGameTitle($game_id)
{
    global $db;
    $query = "SELECT title FROM Game WHERE game_id=:game_id"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':game_id', $game_id);
    $statement->execute();
    $results = $statement->fetch();
    $statement->closeCursor();
    return $results;
}

?>
