-- Database Sistem Peminjaman Alat
CREATE DATABASE IF NOT EXISTS db_peminjaman_alat;
USE db_peminjaman_alat;

DROP TABLE IF EXISTS loans;
DROP TABLE IF EXISTS tools;
DROP TABLE IF EXISTS categories;

-- Tabel 1: categories (kategori alat)
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255)
);

-- Tabel 2: tools (alat), punya foreign key ke categories
CREATE TABLE tools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category_id INT NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

-- Tabel 3: loans (peminjaman), punya foreign key ke tools
CREATE TABLE loans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tool_id INT NOT NULL,
    borrower_name VARCHAR(100) NOT NULL,
    loan_date DATE NOT NULL,
    return_date DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'Dipinjam',
    FOREIGN KEY (tool_id) REFERENCES tools(id)
);

INSERT INTO categories (name, description) VALUES
('Elektronik', 'Alat-alat elektronik'),
('Perkakas', 'Alat tukang dan perbaikan'),
('Multimedia', 'Alat dokumentasi dan presentasi');

INSERT INTO tools (name, category_id, stock) VALUES
('Proyektor', 1, 3),
('Laptop', 1, 4),
('Bor Listrik', 2, 2),
('Kamera DSLR', 3, 2);

INSERT INTO loans (tool_id, borrower_name, loan_date, return_date, status) VALUES
(1, 'Andi', '2026-11-01', '2026-11-03', 'Dikembalikan'),
(2, 'Budi', '2026-11-08', NULL, 'Dipinjam');
