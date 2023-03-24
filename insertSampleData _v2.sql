-- =============================== Game ===============================
-- populate with video_games.csv (https://corgis-edu.github.io/corgis/csv/video_games/)
-- we removed unnecessary columns and renamed column headers for simplicity
-- filw was renamed as temp.csv (temp table imported into our DB)
INSERT INTO Game (genre, title, release_date, unit_price)
SELECT genres, title, CONCAT(year, '-01-01'), price
FROM temp;


-- =============================== User ===============================
INSERT INTO User
VALUES (Null, 'Caroline', 'Patel', 'cpatel', 'P@ssw0rd1!');

INSERT INTO User
VALUES (Null, 'Ethan', 'Nguyen', 'enguyen', 'P@ssw0rd2!');

INSERT INTO User
VALUES (Null, 'Lily', 'Robinson', 'lrobinson', 'P@ssw0rd3!');

INSERT INTO User
VALUES (Null, 'Caleb', 'Kim', 'ckim', 'P@ssw0rd4!');

INSERT INTO User
VALUES (Null, 'Sophia', 'Singh', 'ssingh', 'P@ssw0rd5!');

INSERT INTO User
VALUES (Null, 'Ryan', 'Lee', 'rlee', 'P@ssw0rd6!');

INSERT INTO User
VALUES (Null, 'Ava', 'Brown', 'abrown', 'P@ssw0rd7!');

INSERT INTO User
VALUES (Null, 'Jackson', 'Chen', 'jchen', 'P@ssw0rd8!');

INSERT INTO User
VALUES (Null, 'Mia', 'Davis', 'mdavis', 'P@ssw0rd9!');

INSERT INTO User
VALUES (Null, 'Aiden', 'Garcia', 'agarcia', 'P@ssw0rd10!');

-- =============================== UserEmail ===============================
INSERT INTO UserEmail
VALUES ('cpatel@example.com', 1);

INSERT INTO UserEmail
VALUES ('enguyen@example.com', 2);

INSERT INTO UserEmail
VALUES ('lrobinson@example.com', 3);

INSERT INTO UserEmail
VALUES ('ckim@example.com', 4);

INSERT INTO UserEmail
VALUES ('ssingh@example.com', 5);

INSERT INTO UserEmail
VALUES ('rlee@example.com', 6);

INSERT INTO UserEmail
VALUES ('abrown@example.com', 7);

INSERT INTO UserEmail
VALUES ('jchen@example.com', 8);

INSERT INTO UserEmail
VALUES ('mdavis@example.com', 9);

INSERT INTO UserEmail
VALUES ('agarcia@example.com', 10);

-- =============================== UserPhone ===============================
INSERT INTO UserPhone
VALUES (5551234567, 1);

INSERT INTO UserPhone
VALUES (5552345678, 2);

INSERT INTO UserPhone
VALUES (5553456789, 3);

INSERT INTO UserPhone
VALUES (5554567890, 4);

INSERT INTO UserPhone
VALUES (5555678901, 5);

INSERT INTO UserPhone
VALUES (5556789012, 6);

INSERT INTO UserPhone
VALUES (5557890123, 7);

INSERT INTO UserPhone
VALUES (5558901234, 8);

INSERT INTO UserPhone
VALUES (5559012345, 9);

INSERT INTO UserPhone
VALUES (5550123456, 10);

-- =============================== UserPayment ===============================
INSERT INTO UserPayment
VALUES ('Visa', 4539640001166666, 1);

INSERT INTO UserPayment
VALUES ('Mastercard', 5454545454545454, 2);

INSERT INTO UserPayment
VALUES ('American Express', 378282246310005, 3);

INSERT INTO UserPayment
VALUES ('Visa', 4916550902466762, 4);

INSERT INTO UserPayment
VALUES ('Discover', 6011601160116611, 5);

INSERT INTO UserPayment
VALUES ('Visa', 4556434509122233, 6);

INSERT INTO UserPayment
VALUES ('Mastercard', 5424333333333333, 7);

INSERT INTO UserPayment
VALUES ('American Express', 371449635398431, 8);

INSERT INTO UserPayment
VALUES ('Discover', 6011331346138585, 9);

INSERT INTO UserPayment
VALUES ('Visa', 4916902808771799, 10);

-- =============================== Auctions ===============================


-- =============================== Reviews ===============================


-- =============================== Sold_on ===============================


-- =============================== Purchases ===============================


-- =============================== In_shopping_card ===============================


-- =============================== Sells ===============================
