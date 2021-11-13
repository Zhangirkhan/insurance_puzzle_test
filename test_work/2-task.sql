-- Заходим http://sqlfiddle.com/
-- Вставляем код
-- Нажимаем Build Schema

CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        phone VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)  ENGINE=INNODB;



    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        subtotal INT NOT NULL,
        city_id INT NOT NULL,
        user_id INT NOT NULL,
        created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)  ENGINE=INNODB;



    INSERT users(name, phone, email, created)
    VALUES ('user1', '77777', 'aaa@mail.ru', NOW());
    INSERT users(name, phone, email, created)
    VALUES ('user2', '88888', 'bbb@mail.ru', NOW());
    INSERT users(name, phone, email, created)
    VALUES ('user3', '999999', 'ccc@mail.ru', NOW());

    INSERT orders(subtotal, city_id, user_id, created)
    VALUES (10, 1, 1, NOW());
    INSERT orders(subtotal, city_id, user_id, created)
    VALUES (20, 1, 2, NOW());
    INSERT orders(subtotal, city_id, user_id, created)
    VALUES (30, 1, 1, NOW());


    -- После рядом вставляем следующий код
    -- Нажмаем run SQL
    SELECT * FROM users;
    SELECT * FROM orders;
    SELECT u1.name, u1.phone, IFNULL((SELECT sum(o1.subtotal) FROM orders as o1 where o1.user_id = u1.id),0) as summ, IFNULL((SELECT AVG(o1.subtotal) FROM orders as o1 where o1.user_id = u1.id),0) as AVG, IFNULL((SELECT o1.created FROM orders as o1 where o1.user_id = u1.id ORDER BY o1.created DESC LIMIT 1),0) as last_order_time FROM USERS as u1;
