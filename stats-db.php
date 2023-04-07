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

?>