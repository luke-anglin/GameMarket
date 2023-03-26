-- =============================== Game ===============================
-- populate with video_games.csv (https://corgis-edu.github.io/corgis/csv/video_games/)
-- we removed unnecessary columns and renamed column headers for simplicity
-- filw was renamed as temp.csv (temp table imported into our DB)
INSERT INTO Game (genre, title, release_date, unit_price)
SELECT genres, title, CONCAT(year, '-01-01'), price
FROM temp;


-- =============================== User ===============================


-- =============================== UserEmail ===============================


-- =============================== UserPhone ===============================


-- =============================== UserPayment ===============================


-- =============================== Auctions ===============================


-- =============================== Reviews ===============================


-- =============================== Sold_on ===============================


-- =============================== Purchases ===============================


-- =============================== In_shopping_card ===============================


-- =============================== Sells ===============================
