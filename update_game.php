<?php
require('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $game_id = $_POST['game_id'];
    $title = $_POST['title'];
    $unit_price = $_POST['unit_price'];
    $delete = isset($_POST['delete']);

    // Prepare the SQL statement to update the game
    if ($delete) {
        // If the delete checkbox is checked, delete the game instead
        $stmt = $db->prepare("DELETE FROM Game WHERE game_id = :game_id");
        $stmt->bindValue(':game_id', $game_id);
    } else {
        $stmt = $db->prepare("UPDATE Game SET title = :title, unit_price = :unit_price WHERE game_id = :game_id");
        $stmt->bindValue(':game_id', $game_id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':unit_price', $unit_price, PDO::PARAM_STR);
    }

    // Execute the statement
    $stmt->execute();

    // Redirect back to the home page
    // header('Location: home.php');
    exit();
} else {
    // If the request method is not POST, redirect back to the home page
    header('Location: home.php');
    exit();
}
?>
