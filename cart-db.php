<?php 
// Functions 

// Buy games in cart
//  Add games purchase table with date
//      Delete/Update the corresponding games from Auction, sold_on table
    //  Stock
    //  =1, then delete whole thing
    //  >1, then decrement stock value
    //  Delete game from cart
require_once("connect.php"); 
session_start();

function buyGamesInCart() {
    // get game ids of games we want to buy 
    global $db;
    $uid = $_SESSION['user_id'];
    echo "<p>uid is " . $uid . "</p>";
    $stmt = $db->prepare("SELECT game_id, auction_id, stock
                                FROM In_shopping_cart 
                                NATURAL LEFT JOIN Auctions
                                NATURAL LEFT JOIN Sold_on
                                WHERE user_id = $uid;");
    $stmt->execute(); 
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // for each game...
    echo "<p>Game ids: </p>";
    foreach($results as $result) {
        $game_id = $result['game_id']; 
        $auction_id = $result['auction_id']; 
        $stock = $result['stock'];
        echo "---------------------------<br>";
        echo "game id is $game_id and auction id is $auction_id" . "<br>";

        echo "<p>stock is $stock</p>"; 
        if ($stock > 1) {
            $new_stock = $stock - 1; 
            echo "<p>Stock is > 1. Decrementing stock by one</p>"; 
            // Update auction table such that we decrement auction
            echo "<p>new stock is $new_stock</p>";
            $stmt = $db -> prepare ("UPDATE Auctions 
            SET stock = $new_stock
            WHERE auction_id = $auction_id"); 
            $stmt -> execute();
            echo "<p>Executed!</p>";

        } else {
            echo "<p>Stock is == 1. Deleting auction and corresponding tables.</p>";
            // Tables to make changes to: 
            // Auctions - delete the auction where auction_Id = this auction_id 
            // Sold_on - delete where auction_id = this auction_id 
            // Sells - delete where auction id = this auction id 
            $stmt = $db -> prepare("DELETE FROM Sells
            WHERE auction_id = $auction_id;"); 
            $stmt -> execute(); 
            $stmt = $db -> prepare("DELETE FROM Sold_on
            WHERE auction_id = $auction_id;"); 
            $stmt -> execute();
            $stmt = $db -> prepare("DELETE FROM Auctions 
            WHERE auction_id = $auction_id;");
            $stmt -> execute(); 
        }

    }
    //redirect home
    // header('Location: home.php');

}

buyGamesInCart(); 
?>
