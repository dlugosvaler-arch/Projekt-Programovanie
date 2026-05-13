-- Vytvorenie databázy
CREATE DATABASE IF NOT EXISTS katalog_hier;
USE katalog_hier;

-- 1. Tabuľka: Používatelia
CREATE TABLE IF NOT EXISTS pouzivatelia (
    id INT AUTO_INCREMENT PRIMARY KEY,
    meno VARCHAR(50) NOT NULL
);

-- 2. Tabuľka: Platformy a PC klienti
CREATE TABLE IF NOT EXISTS platformy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazov VARCHAR(50) NOT NULL,
    kategoria VARCHAR(50) -- napr. 'Konzola' alebo 'PC'
);

-- 3. Tabuľka: Hry
CREATE TABLE IF NOT EXISTS hry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazov VARCHAR(100) NOT NULL,
    zaner VARCHAR(50),
    rok_vydania INT,
    platforma_id INT,
    pouzivatel_id INT,
    FOREIGN KEY (platforma_id) REFERENCES platformy(id) ON DELETE CASCADE,
    FOREIGN KEY (pouzivatel_id) REFERENCES pouzivatelia(id) ON DELETE CASCADE
);

-- Vloženie základných dát
INSERT INTO pouzivatelia (meno) VALUES ('Jozef'), ('Andrej');

INSERT INTO platformy (nazov, kategoria) VALUES 
('Playstation', 'Konzola'), 
('Xbox', 'Konzola'), 
('Nintendo', 'Konzola'),
('Steam', 'PC'), 
('Epic Games', 'PC'), 
('Riot', 'PC'),
('Ubisoft', 'PC');