<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
require_once('dbConnection.php');
require_once('function.php');

if (isset($_POST['do_email'])) {

    $data = $_POST;
    $name = trim(htmlspecialchars($data['name']));
    $email = trim(htmlspecialchars($data['email']));
    $textarea = trim(htmlspecialchars($data['textarea']));
    $errors = [];

    if (trim($data['name']) == '') {
        $errors[] = 'Введите Ваше имя!';
    }

    if (trim($data['email']) == '') {
        $errors[] = 'Введите Ваш Email!';
    }

    if (trim($data['textarea']) == '') {
        $errors[] = 'Введите сообщение!';
    }

    if (empty($errors)) {

        $mail = new PHPMailer(true);
        $mail->CharSet = 'utf-8';

        try {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.ru';
            $mail->SMTPAuth = true;
            $mail->Username = 'andreyka.glotov.90@mail.ru';
            $mail->Password = 'Kk778029';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('andreyka.glotov.90@mail.ru');
            $mail->addAddress('norddd@mail.ru');

            $mail->isHTML(true);
            $mail->Subject = 'Сообщение от пользователя магазина';
            $mail->Body    = "Имя: $name<br>Email: $email<br>Сообщение: $textarea";
            $mail->AltBody = '';

            $mail->send();
            header('location: mailsuccess.php');
        } catch (Exception $e) {
            echo "Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $echo_alert = '<div class=\'alert alert-danger\' role=\'alert\'>' . array_shift($errors) . '</div><hr>';
    }
}


?>

<!doctype html>
<html lang="ru" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Форма обратной связи</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body class="d-flex flex-column h-100">

    <header>
        <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">Главная</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarColor01">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="/signup.php">Регистрация <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/login.php">Авторизация</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/mail.php">Почта</a>
                        </li>
                        <li class="nav-item">
                            <?php if (isset($_SESSION['cart_list'])) : ?>
                                <a class="nav-link" href="/cart.php">Корзина: <span class="badge badge-light"><?= count($_SESSION['cart_list']); ?></span></a>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <?php if (isset($_SESSION['loggetUser'])) : ?>
                        <div>
                            <h5 style="color:white;">Вы зашли как: <span class="badge badge-secondary"><?= $_SESSION['loggetUser']['login'] ?></span> <span class="badge badge-secondary"><a href="/logout.php" style="color:white; text-decoration:none;">Выход</a></span></h5>
                        </div>
                        <form class="form-inline ml-2" action="/search.php" method="GET">
                            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Поиск" aria-label="Search">
                            <button class="btn btn-primary my-2 my-sm-0" type="submit">Поиск</button>
                        </form>
                    <?php else : ?>
                        <form class="form-inline" action="/search.php" method="GET">
                            <input class="form-control mr-sm-2" type="search" name="search" placeholder="Поиск" aria-label="Search">
                            <button class="btn btn-primary my-2 my-sm-0" type="submit">Поиск</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main role="main" class="flex-shrink-0 mt-5">
        <h1 class="mt-5 mb-5">
            <center>Форма обратной связи</center>
        </h1>
        <div class="container">
            <?php if (isset($echo_alert)) : ?>
                <?= $echo_alert ?>
            <?php endif; ?>
            <form action="/mail.php" method="post">
                <div class="form-group">
                    <label for="InputUserName">Имя:</label>
                    <input type="text" class="form-control" name="name" id="InputUserName" placeholder="Введите имя" value="<?= @$data['name'] ?>">
                </div>
                <div class="form-group">
                    <label for="InputEmail">Email:</label>
                    <input type="email" class="form-control" name="email" id="InputEmail" placeholder="Введите Email" value="<?= @$data['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="FormControlTextarea">Сообщение:</label>
                    <textarea class="form-control" name="textarea" id="FormControlTextarea" placeholder="Введите Сообщение" rows="3"></textarea>
                </div>
                <button type="submit" name="do_email" class="btn btn-success mt-3 mb-3">Отправить</button>
            </form>
        </div>
    </main>

    <footer class="py-5 mt-auto bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Мой тестовый сайт</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html>