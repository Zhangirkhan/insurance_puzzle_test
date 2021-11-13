<?php

// 1. Оптимизируй

// ...
$questionsQ = $mysqli->query('SELECT * FROM questions WHERE catalog_id='. $catId);
$result = array();
while ($question = $questionsQ->fetch_assoc()) {
	$userQ = $mysqli->query('SELECT name, gender FROM users WHERE id='. $question['user_id']);
	$user = $userQ->fetch_assoc();
	$result[] = array('question'=>$question, 'user'=>$user);
	$userQ->free();
}
$questionsQ->free();
// ...

// Для проверки
// CREATE TABLE IF NOT EXISTS questions (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         question VARCHAR(255) NOT NULL,
//          title VARCHAR(255) NOT NULL,
//          catalog_id INT NOT NULL,
//         user_id INT NOT NULL,
// )  ENGINE=INNODB;


// CREATE TABLE IF NOT EXISTS users (
//         id INT AUTO_INCREMENT PRIMARY KEY,
//         name VARCHAR(255) NOT NULL,
//         gender INT NOT NULL,
// )  ENGINE=INNODB;


//  INSERT questions(question, title, catalog_id, user_id)
//     VALUES ('test1', '11111', 1, 1);
//     INSERT questions(question, title, catalog_id, user_id)
//     VALUES ('test2', '222', 2, 2);
//     INSERT questions(question, title, catalog_id, user_id)
//     VALUES ('test3', '333', 1, 2);

//      INSERT users(name, gender)
//     VALUES ('user1', 1);
//     INSERT users(name, gender)
//     VALUES ('user2', 0);
//     INSERT users(name, gender)
//     VALUES ('user3', 1);


    # https://www.php.net/manual/en/mysqli-stmt.fetch.php#82742
    function stmt_bind_assoc(&$stmt, &$out)
        {
            $data = mysqli_stmt_result_metadata($stmt);
            $fields = array();
            $out = array();

            $fields[0] = $stmt;
            $count = 1;

            while ($field = mysqli_fetch_field($data)) {
                $fields[$count] = &$out[$field->name];
                $count++;
            }
            call_user_func_array(mysqli_stmt_bind_result, $fields);
        }


        $stmt = $mysqli->prepare("SELECT t1.* ,t2.name,t2.gender FROM questions t1 JOIN  users t2 ON  t1.user_id = t2.id  where t1.catalog_id=?");
        $stmt->bind_param('i', intval($catId));
        $stmt->execute();
        $stmt->store_result();

        $resultrow = array();
        stmt_bind_assoc($stmt, $resultrow);
        while ($stmt->fetch()) {
            print_r($resultrow);
        }

        $stmt->free_result();
        $stmt->close();
        $mysqli->close();



// 2. Напиши SQL-запрос
// Имеем следующие таблицы:
// 1.	users — контрагенты
// 1.	id
// 2.	name
// 3.	phone
// 4.	email
// 5.	created — дата создания записи
// 2.	orders — заказы
// 1.	id
// 2.	subtotal — сумма всех товарных позиций
// 3.	created — дата и время поступления заказа (Y-m-d H:i:s)
// 4.	city_id — город доставки
// 5.	user_id

// Необходимо выбрать одним запросом следующее (следует учесть, что могут быть контрагенты, не сделавшие ни одного заказа):
// 1.	Имя контрагента
// 2.	Его телефон
// 3.	Сумма всех его заказов
// 4.	Его средний чек
// 5.	Дата последнего заказа

// -- Заходим http://sqlfiddle.com/
// -- Вставляем код
// -- Нажимаем Build Schema

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


    // -- После рядом вставляем следующий код
    // -- Нажмаем run SQL
    SELECT * FROM users;
    SELECT * FROM orders;

    //2 Варианта
    SELECT u1.name, u1.phone, IFNULL((SELECT sum(o1.subtotal) FROM orders as o1 where o1.user_id = u1.id),0) as summ, IFNULL((SELECT AVG(o1.subtotal) FROM orders as o1 where o1.user_id = u1.id),0) as AVG, IFNULL((SELECT o1.created FROM orders as o1 where o1.user_id = u1.id ORDER BY o1.created DESC LIMIT 1),0) as last_order_time FROM USERS as u1;

SELECT
           t2.name,t2.phone,
           IFNULL(SUM(t1.subtotal), 0) as SUMM ,IFNULL(avg(t1.subtotal)) , 0) as MIDDLE,IFNULL(max(t1.created)) as MAXIMUM, 'Еще не заказывал')
        FROM orders t1  right join users t2 on  t1.user_id = t2.id
        GROUP by t2.id

// 3. Создай небольшой сервис по страхованию

// Необходимо создать таблицы в базе данных MYSQL и наполнить их данными  из приложенных файлов,
// после чего создать небольшой веб-интерфейс для возможности покупки страховых услуг,
// сумма страховки клиент выбирает самостоятельно, сумма покупки страхового полиса рассчитывается
// из приведенных данных, «поправочные коэффициенты» можно игнорировать, на дизайн и исполнение
// frontend тимлид также не будет обращать внимания. Необходимо руководствоваться принципами
//  KISS, DRY и SOLID при написании кода.

// https://github.com/Zhangirkhan/insurance_puzzle_test
