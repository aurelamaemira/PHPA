CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);


CREATE TABLE products (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    category_id INT NOT NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO categories (id, name) VALUE
(1, 'Fruit'),
(2, 'Bakery'),
(3, 'Dry goods'),
(4, 'Vegetable');

INSERT INTO products (id,name,category_id) VALUES

(1, 'Apple',1),
(2, 'Bananas'1),
(3, 'Bread'2),
(4, 'Cake'2),
(1, 'Pasta',3),
(2, 'Rice'3);
