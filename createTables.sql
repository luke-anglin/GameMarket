-- SQL commands to create all tables
CREATE TABLE User (
    user_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    username VARCHAR(100),
    p_word VARCHAR(100)
);

CREATE TABLE UserEmail (
    email_address VARCHAR(255) PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TABLE UserPhone (
    phone_number BIGINT PRIMARY KEY,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TABLE UserPayment (
    card_number BIGINT PRIMARY KEY,
    card_type VARCHAR(30),
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES User(user_id)
);

CREATE TABLE Game (
  game_id INT PRIMARY KEY AUTO_INCREMENT,
  genre VARCHAR(255),
  avg_rating FLOAT DEFAULT NULL,
  title VARCHAR(255),
  release_date DATE,
  unit_price DECIMAL(4,2)
);

CREATE TABLE Auctions (
    auction_id INT PRIMARY KEY AUTO_INCREMENT,
    price DECIMAL(4,2),
    stock INT
);

CREATE TABLE Reviews (
    user_id INT,
    game_id INT,
    rating FLOAT,
    PRIMARY KEY (user_id, game_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

CREATE TABLE Sold_on (
    auction_id INT PRIMARY KEY,
    game_id INT,
    FOREIGN KEY (auction_id) REFERENCES Auctions(auction_id),
    FOREIGN KEY (game_id) REFERENCES Game(game_id)
);

CREATE TABLE Purchases (
    user_id INT,
    auction_id INT,
    purchase_date DATE,
    PRIMARY KEY (user_id, auction_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (auction_id) REFERENCES Auctions(auction_id)
);

CREATE TABLE In_shopping_cart (
    user_id INT,
    auction_id INT,
    PRIMARY KEY (user_id, auction_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (auction_id) REFERENCES Auctions(auction_id)
);

CREATE TABLE Sells (
    user_id INT,
    auction_id INT,
    PRIMARY KEY (user_id, auction_id),
    FOREIGN KEY (user_id) REFERENCES User(user_id),
    FOREIGN KEY (auction_id) REFERENCES Auctions(auction_id)
);
