<?php

    // Позволяем использывать header ы
    ob_start();

    // Создаем сессию
    if(!isset($_SESSION)) {
        session_start();
    }
    //Настройки базы данных
    $hostname = "localhost";
    $username = "root";
    $password = "";
    $dbname = "puzzle_test";

    $connection = mysqli_connect($hostname, $username, $password, $dbname) or die("Подключение к базе данных не стабильно.")

?>
