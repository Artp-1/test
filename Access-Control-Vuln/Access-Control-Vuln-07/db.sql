USE asdf;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(40) NOT NULL,
    password VARCHAR(80) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0
);

INSERT INTO users(username, password, is_admin) VALUES
('administrator', '4@5@$fsa@!@#', 1),
('bob', 'bob123', 0),
('sophia', 'jdand2!ca', 0),
('mia', 'reden2123!', 0),
('maria', 'maria22003', 0),
('maya', 'mayamaya22020', 0),
('zoe', 'zoe!@#1223', 0),
('harvey', 'harvey22x12', 0);