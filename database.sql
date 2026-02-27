CREATE DATABASE IF NOT EXISTS praktikum3;
USE praktikum3;

CREATE TABLE IF NOT EXISTS mahasiswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nrp VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    jurusan VARCHAR(100) NOT NULL
);

INSERT INTO mahasiswa (nama, nrp, email, jurusan) VALUES
('John Doe', '12345678', 'john@example.com', 'Teknik Informatika'),
('Jane Smith', '87654321', 'jane@example.com', 'Sistem Informasi');
