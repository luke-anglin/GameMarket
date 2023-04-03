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

?>