<?php

$driver = 'mysql';
$host = 'localhost';
$db_name = 'shop';
$db_user = 'root';
$db_pass = 'root';
$charset = 'utf8';
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_EMULATE_PREPARES => false];


try {
        $pdo = new PDO("$driver:host=$host;dbname=$db_name;charset=$charset", $db_user, $db_pass, $options);

        session_start();

} catch (PDOException $e) {
        die("Ошибка в подключении к базе данных!<br><br>Вывод ошибки:<pre>$e");
}

