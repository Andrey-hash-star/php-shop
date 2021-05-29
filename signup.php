<?php
require_once('dbConnection.php');
require_once('function.php');

if (isset($_POST['do_signup'])) {
    $data = $_POST;
    $login = trim(htmlspecialchars($data['login']));
    $email = trim(htmlspecialchars($data['email']));
    $password = trim(htmlspecialchars(password_hash($data['password'], PASSWORD_DEFAULT)));
    $errors = [];

    if (trim($data['login']) == '') {
        $errors[] = 'Введите логин!';
    }

    if (trim($data['email']) == '') {
        $errors[] = 'Введите Email!';
    }

    if (trim($data['password']) == '') {
        $errors[] = 'Введите пароль!';
    }

    if (trim($data['password_2']) != trim($data['password'])) {
        $errors[] = 'Повторный пароль введен не верно!';
    }

    $sql1 = "SELECT COUNT(login) FROM users WHERE login = '$login'";
    $count_login = $pdo->query($sql1);
    $count_array_login = $count_login->fetch(PDO::FETCH_NUM);
    $count1 = $count_array_login[0];

    if ($count1 > 0) {
        $errors[] = 'Такой логин уже существует!';
    }

    $sql2 = "SELECT COUNT(email) FROM users WHERE email = '$email'";
    $count_email = $pdo->query($sql2);
    $count_array_email = $count_email->fetch(PDO::FETCH_NUM);
    $count2 = $count_array_email[0];

    if ($count2 > 0) {
        $errors[] = 'Такой Email уже существует!';
    }

    if (empty($errors)) {

        $sql3 = "INSERT INTO users (login, email, password) VALUES (?, ?, ?)";

        $stmt = $pdo->prepare($sql3);
        $params = [$login, $email, $password];
        $stmt->execute($params);

        $echo_alert = "<div class='alert alert-success' role='alert'>Вы успешно зарегистрированы!</div><hr>";
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
    <title>Результаты поиска</title>
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
            <center>Регистрация</center>
        </h1>
        <div class="container">
            <?php if (isset($echo_alert)) : ?>
                <?= $echo_alert ?>
            <?php endif; ?>
            <form action="/signup.php" method="post">
                <div class="form-group">
                    <label for="InputUserName">Логин:</label>
                    <input type="text" class="form-control" name="login" id="InputUserName" placeholder="Введите логин" value="<?= @$data['login'] ?>">
                </div>
                <div class="form-group">
                    <label for="InputEmail">Email:</label>
                    <input type="email" class="form-control" name="email" id="InputEmail" placeholder="Введите Email" value="<?= @$data['email'] ?>">
                </div>
                <div class="form-group">
                    <label for="InputPassword">Пароль:</label>
                    <input type="password" class="form-control" name="password" id="InputPassword" placeholder="Введите пароль" value="<?= @$data['password'] ?>">
                </div>
                <div class="form-group">
                    <label for="InputPassword_2">Введите Ваш пароль еще раз:</label>
                    <input type="password" class="form-control" name="password_2" id="InputPassword_2" placeholder="Введите Ваш пароль еще раз" value="<?= @$data['password_2'] ?>">
                </div>
                <button type="submit" name="do_signup" class="btn btn-success mt-3 mb-3">Зарегистрироваться</button>
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