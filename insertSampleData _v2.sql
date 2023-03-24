-- =============================== Game ===============================
-- populate with video_games.csv (https://corgis-edu.github.io/corgis/csv/video_games/)
-- we removed unnecessary columns and renamed column headers for simplicity
-- filw was renamed as temp.csv (temp table imported into our DB)
INSERT INTO Game (genre, title, release_date, unit_price)
SELECT genres, title, CONCAT(year, '-01-01'), price
FROM temp;


-- =============================== User ===============================
INSERT INTO User
VALUES (0, 'Caroline', 'Patel', 'cpatel', 'P@ssw0rd1!');

INSERT INTO User
VALUES (1, 'Ethan', 'Nguyen', 'enguyen', 'P@ssw0rd2!');

INSERT INTO User
VALUES (2, 'Lily', 'Robinson', 'lrobinson', 'P@ssw0rd3!');

INSERT INTO User
VALUES (3, 'Caleb', 'Kim', 'ckim', 'P@ssw0rd4!');

INSERT INTO User
VALUES (4, 'Sophia', 'Singh', 'ssingh', 'P@ssw0rd5!');

INSERT INTO User
VALUES (5, 'Ryan', 'Lee', 'rlee', 'P@ssw0rd6!');

INSERT INTO User
VALUES (6, 'Ava', 'Brown', 'abrown', 'P@ssw0rd7!');

INSERT INTO User
VALUES (7, 'Jackson', 'Chen', 'jchen', 'P@ssw0rd8!');

INSERT INTO User
VALUES (8, 'Mia', 'Davis', 'mdavis', 'P@ssw0rd9!');

INSERT INTO User
VALUES (9, 'Aiden', 'Garcia', 'agarcia', 'P@ssw0rd10!');

-- =============================== UserEmail ===============================
INSERT INTO UserEmail
VALUES (0, 'cpatel@example.com');

INSERT INTO UserEmail
VALUES (1, 'enguyen@example.com');

INSERT INTO UserEmail
VALUES (2, 'lrobinson@example.com');

INSERT INTO UserEmail
VALUES (3, 'ckim@example.com');

INSERT INTO UserEmail
VALUES (4, 'ssingh@example.com');

INSERT INTO UserEmail
VALUES (5, 'rlee@example.com');

INSERT INTO UserEmail
VALUES (6, 'abrown@example.com');

INSERT INTO UserEmail
VALUES (7, 'jchen@example.com');

INSERT INTO UserEmail
VALUES (8, 'mdavis@example.com');

INSERT INTO UserEmail
VALUES (9, 'agarcia@example.com');

-- =============================== UserPhone ===============================
INSERT INTO UserPhone
VALUES (0, 5551234567);

INSERT INTO UserPhone
VALUES (1, 5552345678);

INSERT INTO UserPhone
VALUES (2, 5553456789);

INSERT INTO UserPhone
VALUES (3, 5554567890);

INSERT INTO UserPhone
VALUES (4, 5555678901);

INSERT INTO UserPhone
VALUES (5, 5556789012);

INSERT INTO UserPhone
VALUES (6, 5557890123);

INSERT INTO UserPhone
VALUES (7, 5558901234);

INSERT INTO UserPhone
VALUES (8, 5559012345);

INSERT INTO UserPhone
VALUES (9, 5550123456);

-- =============================== UserPayment ===============================
INSERT INTO UserPayment
VALUES (0, 'Visa', 4539640001166666);

INSERT INTO UserPayment
VALUES (1, 'Mastercard', 5454545454545454);

INSERT INTO UserPayment
VALUES (2, 'American Express', 378282246310005);

INSERT INTO UserPayment
VALUES (3, 'Visa', 4916550902466762);

INSERT INTO UserPayment
VALUES (4, 'Discover', 6011601160116611);

INSERT INTO UserPayment
VALUES (5, 'Visa', 4556434509122233);

INSERT INTO UserPayment
VALUES (6, 'Mastercard', 5424333333333333);

INSERT INTO UserPayment
VALUES (7, 'American Express', 371449635398431);

INSERT INTO UserPayment
VALUES (8, 'Discover', 6011331346138585);

INSERT INTO UserPayment
VALUES (9, 'Visa', 4916902808771799);

-- =============================== Auctions ===============================


-- =============================== Reviews ===============================


-- =============================== Sold_on ===============================


-- =============================== Purchases ===============================


-- =============================== In_shopping_card ===============================


-- =============================== Sells ===============================
