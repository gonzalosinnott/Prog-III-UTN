CREATE TABLE users (
    id int NOT NULL AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    surname varchar(255) NOT NULL,
    pass int NOT NULL,
    mail varchar(255) NOT NULL,
    location varchar(255) NOT NULL,
    registerDate DATE NOT NULL,
	PRIMARY KEY (id)
);