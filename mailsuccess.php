<?php

require_once('dbConnection.php');
require_once('function.php');

?>
<!doctype html>
<html lang="ru" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Письмо успешно отправлено!</title>
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
        <div class="container">
            <h1 class="mt-5 mb-5">
                <center>Ваше сообщение успешно отправлено!</center>
            </h1>
            <a class="btn btn-success btn-lg btn-block mt-5 mb-3" role="button" href="/mail.php">Отправить еще одно сообщение</a>
            <a class="btn btn-success btn-lg btn-block mt-3 mb-3" role="button" href="/">Продолжить покупки</a>
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