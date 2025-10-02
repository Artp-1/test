USE asdf;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(40) NOT NULL,
    password VARCHAR(80) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0
);

INSERT INTO users(username, password, is_admin) VALUES
('administrator', '!@AEWsqD@!2!', 1),
('bob', 'bob123', 0),
('sophia', 'jdand@!ca', 0),
('mia', 'reden123!', 0),
('maria', 'maria2003', 0),
('maya', 'mayamaya2020', 0),
('zoe', 'zoe!@#123', 0),
('harvey', 'harvey212', 0);