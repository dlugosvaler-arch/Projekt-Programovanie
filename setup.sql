-- Vytvorenie databázy (ak ju ešte nemáš vytvorenú v phpMyAdmin)
CREATE DATABASE IF NOT EXISTS katalog_hier;
USE katalog_hier;

-- Tabuľka 1: Platformy (napr. PC, PlayStation)
CREATE TABLE IF NOT EXISTS platformy (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazov VARCHAR(50) NOT NULL
);

-- Tabuľka 2: Hry (s prepojením na platformu)
CREATE TABLE IF NOT EXISTS hry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nazov VARCHAR(100) NOT NULL,
    zaner VARCHAR(50),
    rok_vydania INT,
    platforma_id INT,
    FOREIGN KEY (platforma_id) REFERENCES platformy(id) ON DELETE CASCADE
);

-- Vloženie dummy dát pre testovací účel
INSERT INTO platformy (nazov) VALUES ('PC'), ('PlayStation 5'), ('Xbox Series X');
INSERT INTO hry (nazov, zaner, rok_vydania, platforma_id) VALUES 
('The Witcher 3', 'RPG', 2015, 1),
('God of War Ragnarök', 'Akčná', 2022, 2);