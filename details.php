<?php

require_once('dbConnection.php');
require_once('function.php');
require_once('rate.php');

if (isset($_GET['id'])) {

    $id = $_GET['id'];
    $table = "products";

    $stmt = $pdo->prepare("SELECT * FROM $table  WHERE id = :id ");

    $params = [':id' => $id];
    $stmt->execute($params);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);
}


$arr = [];
if (isset($_SESSION['cart_list'])) {
    foreach ($_SESSION['cart_list'] as $product_cart) {
        global $arr;
        $arr[] = $product_cart['price'];
    }
}
?>


<!DOCTYPE html>
<html lang="ru" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Конкретный товар</title>
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
                            <h4 style="color:white;">Вы зашли как: <span class="badge badge-secondary"><?= $_SESSION['loggetUser']['login']; ?></span> <span class="badge badge-secondary"><a href="/logout.php" style="color:white; text-decoration:none;">Выход</a></span></h4>
                        </div>
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
            <div class="row">
                <div class="col-lg-9">
                    <div class="card mt-4">
                        <center>
                            <img style="max-width: 40%;" class="card-img-top img-fluid mt-2" src="/img/<?= show_img($product['img']); ?>" alt="">
                        </center>
                        <div class="card-body">
                            <hr>
                            <h3 class="card-title"><?= $product['name']; ?></h3>
                            <hr>
                            <h4><?= $product['price']; ?> рублей</h4>
                            <p class="card-text"><?= $product['description']; ?></p>
                            <a href="/cart.php/?product_id=<?= $product['id']; ?>" class="btn btn-success">Добавить в корзину</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 order-md-2 mb-4 mt-4">
                    <?php if (isset($_SESSION['loggetUser'])) : ?>
                        <form class="card p-2 mb-5" action="/search.php" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="search" name="search" placeholder="Поиск" aria-label="Search">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-primary">Поиск</button>
                                </div>
                            </div>
                        </form>
                    <?php endif; ?>
                    <h4 class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted"><a href="/cart.php" class="badge badge-secondary">Ваша корзина</a></span> <span class="badge badge-secondary badge-pill"><?= count($_SESSION['cart_list']); ?></span>
                    </h4>
                    <ul class="list-group mb-3">
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?= $rate->Valute->USD->CharCode; ?></h6>
                                <small class="text-muted">Обменный курс <?= $rate->Valute->USD->Name; ?> по ЦБ РФ на сегодня</small>
                            </div>
                            <span class="text-muted">₽<?= $rate->Valute->USD->Value; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?= $rate->Valute->EUR->CharCode; ?></h6>
                                <small class="text-muted">Обменный курс <?= $rate->Valute->EUR->Name; ?> по ЦБ РФ на сегодня</small>
                            </div>
                            <span class="text-muted">₽<?= $rate->Valute->EUR->Value; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between lh-condensed">
                            <div>
                                <h6 class="my-0"><?= $rate->Valute->CNY->CharCode; ?></h6>
                                <small class="text-muted">Обменный курс <?= $rate->Valute->CNY->Name; ?> по ЦБ РФ на сегодня</small>
                            </div>
                            <span class="text-muted">₽<?= $rate->Valute->CNY->Value; ?></span>
                        </li>
                    </ul>
                    <ul class="list-group mb-3">
                        <?php if (count($_SESSION['cart_list']) == null) : ?>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0">Корзина пуста</h6>
                                </div>
                            </li>
                        <?php else : ?>
                            <?php foreach ($_SESSION['cart_list'] as $product_cart) : ?>
                                <?php if (isset($_SESSION['discount'])) : ?>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0"><?= $product_cart['name']; ?></h6>
                                        </div>
                                        <span class="text-muted"><?= ceil($product_cart['price'] - ($product_cart['price'] * $_SESSION['discount']['discount'] / 100)); ?> рублей</span>
                                    </li>
                                <?php else : ?>
                                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                                        <div>
                                            <h6 class="my-0"><?= $product_cart['name']; ?></h6>
                                        </div>
                                        <span class="text-muted"><?= $product_cart['price']; ?> рублей</span>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php if (isset($_SESSION['discount'])) : ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Сумма (руб)</span>
                                    <strong><?= ceil(array_sum($arr) - (array_sum($arr) * $_SESSION['discount']['discount'] / 100)); ?> рублей</strong>
                                </li>
                            <?php else : ?>
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Сумма (руб)</span>
                                    <strong><?= array_sum($arr); ?> рублей</strong>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>
                    <?php if (isset($_GET['error_promo'])) : ?>
                        <div class='alert alert-danger' role='alert'>Введен неверный промокод!</div>
                        <hr>
                    <?php endif; ?>
                    <?php if (isset($_GET['success_promo'])) : ?>
                        <div class='alert alert-success' role='alert'>Промокод "<?= $_SESSION['discount']['name']; ?>" успешно подтвержден!</div>
                        <hr>
                    <?php endif; ?>
                    <form class="card p-2" action="/discounts.php/?id=<?= $id; ?>" method="post">
                        <div class="input-group">
                            <input type="text" name="promo" class="form-control" placeholder="Промокод">
                            <div class="input-group-append">
                                <button type="submit" name="do_details_promo" class="btn btn-secondary">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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