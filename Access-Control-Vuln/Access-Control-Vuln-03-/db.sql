USE asdf;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(40) NOT NULL,
    password VARCHAR(80) NOT NULL,
    email VARCHAR(100) DEFAULT '',
    roleid INT DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    bio TEXT
);

INSERT INTO users(username, password, email, roleid, is_admin) VALUES
('administrator', '!@AEWsqD@!2!', 'admin@example.com', 1, 1),
('socora', 'socora', 'socora@example.com', 0, 0),
('sophia', 'jdand@!ca', 'sophia@example.com', 0, 0),
('mia', 'reden123!', 'mia@example.com', 0, 0),
('maria', 'maria2003', 'maria@example.com', 0, 0),
('maya', 'mayamaya2020', 'maya@example.com', 0, 0),
('zoe', 'zoe!@#123', 'zoe@example.com', 0, 0),
('harvey', 'harvey212', 'harvey@example.com', 0, 0);
