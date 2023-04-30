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

function deletefromCart($user_id, $auction_id)
{
    global $db;
    $query = "DELETE FROM In_shopping_cart WHERE user_id=:user_id AND auction_id=:auction_id;"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->bindValue(':auction_id', $auction_id);
    $statement->execute();
    $statement->closeCursor();
}

function checkPayment($user_id)
{
    global $db;
    $query = "SELECT COUNT(*) as p FROM UserPayment WHERE user_id=:user_id;"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $user_id);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

function buyGamesInCart() {
    global $db;
    $uid = $_SESSION['user_id'];
    
    // Get all auctions/game in the shopping cart
    $query = "SELECT auction_id, game_id, stock, (SELECT user_id FROM Sells WHERE auction_id = (SELECT auction_id FROM In_shopping_cart WHERE user_id = :user_id)) as user_id
                    FROM In_shopping_cart NATURAL LEFT JOIN Sold_on NATURAL LEFT JOIN Auctions
                    WHERE user_id=:user_id;"; 
    $statement = $db->prepare($query);
    $statement->bindValue(':user_id', $uid);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();

    // Add each game to the purchase table and remove from shopping cart
    foreach ($results as $result){
        $seller = $result['user_id'];
        $game = $result['game_id'];
        $auction = $result['auction_id'];
        $stock = $result['stock'];
        $date = date("Y-m-d");

        // Get title of game and seller username
        $query = "SELECT title, (SELECT username FROM User WHERE user_id = :seller_id) as sellname
                    FROM Game
                    WHERE game_id = :game_id;";
        $statement = $db->prepare($query);
        $statement->bindValue(':game_id', $game);
        $statement->bindValue(':seller_id', $seller);
        $statement->execute();
        $names = $statement->fetchAll();
        $statement->closeCursor();
        foreach ($names as $n){
            $game_title = $n['title'];
            $seller_name = $n['sellname'];
        }


        // Insert into purchases table/make purchase if game was not purchased from the same seller on the same date
        $query = "SELECT COUNT(*) as purchase_count
                    FROM Purchases
                    WHERE user_id = :user_id AND purchase_date = :purchase_date AND game_id = :game_id AND seller_id = :seller_id;";
        $statement = $db->prepare($query);
        $statement->bindValue(':user_id', $uid);
        $statement->bindValue(':purchase_date', $date);
        $statement->bindValue(':game_id', $game);
        $statement->bindValue(':seller_id', $seller);
        $statement->execute();
        $purchased = $statement->fetchAll();
        $statement->closeCursor();

        foreach ($purchased as $p){
            if ($p['purchase_count'] > 0){
                $message = 'You have already purchased "' . $game_title . '" from "' . $seller_name . '". Please try again tomorrow.';
                echo "<script>alert('$message');</script>";
                exit();
            }
            else{
                $query = "INSERT INTO Purchases (user_id, purchase_date, game_id, seller_id)
                    VALUES (:user_id, :purchase_date, :game_id, :seller_id);";
                $statement = $db->prepare($query);
                $statement->bindValue(':user_id', $uid);
                $statement->bindValue(':purchase_date', $date);
                $statement->bindValue(':game_id', $game);
                $statement->bindValue(':seller_id', $seller);
                $statement->execute();
                $statement->closeCursor();

                // Delete from shopping cart
                $query = "DELETE FROM In_shopping_cart
                            WHERE user_id = :user_id AND auction_id = :auction_id;";
                $statement = $db->prepare($query);
                $statement->bindValue(':user_id', $uid);
                $statement->bindValue(':auction_id', $auction);
                $statement->execute();
                $statement->closeCursor();

                // Update the Auction
                if ($stock == 1){
                    $query = "DELETE FROM Auctions
                                WHERE auction_id = :auction_id;";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':auction_id', $auction);
                    $statement->execute();
                    $statement->closeCursor();
                }
                elseif ($stock > 1){
                    $query = "UPDATE Auctions
                                SET stock = stock - 1
                                WHERE auction_id = :auction_id;";
                    $statement = $db->prepare($query);
                    $statement->bindValue(':auction_id', $auction);
                    $statement->execute();
                    $statement->closeCursor();
                }
            }
        }
        
    }

    /*
    // echo "<p>uid is " . $uid . "</p>";
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
            // add purhcase 
            $stmt = $db -> prepare ("INSERT INTO Purchases(user_id, auction_id, purchase_date) VALUES ($uid, $auction_id, CURDATE())");
            $stmt->execute();
            
        } else {
            echo "<p>Stock is == 1. Deleting auction and corresponding tables.</p>";
            // Tables to make changes to: 
            // Auctions - delete the auction where auction_Id = this auction_id 
            // Sold_on - delete where auction_id = this auction_id 
            // Sells - delete where auction id = this auction id 
            // Get everything from purchases 
            // add new purchase
            echo "purchase insert with uid $uid and auction_id $auction_id<br>";
            $stmt = $db -> prepare ("INSERT INTO Purchases(user_id, auction_id, purchase_date) VALUES ($uid, $auction_id, CURDATE())");
            $stmt -> execute();
            
            $stmt = $db -> prepare("DELETE FROM In_shopping_cart
            WHERE auction_id = $auction_id;"); 
            $stmt -> execute(); 
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
    */
}

?>
