USE asdf;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(40) NOT NULL,
    password VARCHAR(80) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0
);

INSERT INTO users(username, password, is_admin) VALUES
('administrator', '!#AEWascjknas@', 1),
('bob', 'bob123', 0),
('sophia', 'jdand!!ca', 0),
('mia', 'reden312!', 0),
('maria', 'maria2005', 0),
('maya', 'mayamaya2021', 0),
('zoe', 'zoe!@#321', 0),
('harvey', 'harvey2321', 0);
