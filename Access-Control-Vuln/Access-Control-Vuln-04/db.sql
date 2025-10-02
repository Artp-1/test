USE asdf;

DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id CHAR(36) PRIMARY KEY DEFAULT (UUID()),
    username VARCHAR(40) NOT NULL,
    password VARCHAR(80) NOT NULL,
    email VARCHAR(100) DEFAULT '',
    is_admin TINYINT(1) DEFAULT 0,
    bio TEXT,
    api_key CHAR(36) NOT NULL DEFAULT (UUID())
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id CHAR(36) NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO users (username, password, email, bio, is_admin) VALUES
('administrator', '!@AEWsq@#@!@!', 'admin@example.com', '', 1),
('bob', 'bob123', 'bob@example.com', '', 0),
('sophia', 'jdand@!ca', 'sophia@example.com', '', 0),
('mia', 'reden123!', 'mia@example.com', '', 0),
('maria', 'maria2003', 'maria@example.com', '', 0),
('maya', 'mayamaya2020', 'maya@example.com', '', 0),
('zoe', 'zoe!@#123', 'zoe@example.com', '', 0),
('harvey', 'harvey212', 'harvey@example.com', '', 0);
