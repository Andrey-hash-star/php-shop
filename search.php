<?php

require_once('dbConnection.php');
require_once('function.php');
require_once('rate.php');

if (isset($_GET['search'])) {

    $search = htmlspecialchars($_GET['search']);

    $page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 1;
    $prev = $page - 1;
    $next = $page + 1;

    if (ctype_digit($page) === false) $page = 1;

    $table1 = "products";
    $table2 = "categories";
    $count_query = $pdo->query("SELECT COUNT(*) FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%'");
    $count_array = $count_query->fetch(PDO::FETCH_NUM);
    $count = $count_array[0];
    $limit = 6;
    $start = ($page * $limit) - $limit;
    $length = ceil($count / $limit);


    if ((int) $page > $length || $page <= 0) $start = 0;


    if (isset($_GET['do_new']) || $_GET['do_sorting'] == 'do_new') {

        $do_sorting = 'do_new';

        $stmt = $pdo->prepare("SELECT categories.name AS name_cat, products.id, products.name, products.description, products.price, products.discount, products.img  FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%' ORDER BY id DESC LIMIT ?, ?");
    } elseif (isset($_GET['do_old']) || $_GET['do_sorting'] == 'do_old') {

        $do_sorting = 'do_old';

        $stmt = $pdo->prepare("SELECT categories.name AS name_cat, products.id, products.name, products.description, products.price, products.discount, products.img  FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%' ORDER BY id ASC LIMIT ?, ?");
    } elseif (isset($_GET['do_price']) || $_GET['do_sorting'] == 'do_price') {

        $do_sorting = 'do_price';

        $stmt = $pdo->prepare("SELECT categories.name AS name_cat, products.id, products.name, products.description, products.price, products.discount, products.img  FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%' ORDER BY price DESC LIMIT ?, ?");
    } else {

        $stmt = $pdo->prepare("SELECT categories.name AS name_cat, products.id, products.name, products.description, products.price, products.discount, products.img  FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.name LIKE '%$search%' OR categories.name LIKE '%$search%' ORDER BY id DESC LIMIT ?, ?");
    }


    $params = [$start, $limit];
    $stmt->execute($params);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    function Pagination($length, $page, $search)
    {
        global $do_sorting;

        foreach (range(1, $length) as $p) {
            if ($page == $p) {
                echo '<li class="page-item active"><a class="page-link" href="http://shop/search.php/' . '?search=' . $search . '&page=' . $p . '&do_sorting=' . $do_sorting . '">' . $p . '</a></li>';
            } else {
                echo
                    '<li class="page-item"><a class="page-link" href="http://shop/search.php/' . '?search=' . $search . '&page=' . $p . '&do_sorting=' . $do_sorting . '">' . $p . '</a></li>';
            }
        }
    }
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
        <div class="container mt-4">
            <div class="row">
                <div class="col-lg-9">
                    <?php if (!$rows || $search === "") : ?>
                        <center>
                            <h3>Ничего не найдено!</h3>
                            <a class="btn btn-success btn-lg btn-block mt-5 mb-5" role="button" href="/">Вернуться и продолжить покупки</a>
                        </center>
                    <?php else : ?>
                        <center>
                            <h3 class="mb-4">Ваш поисковый запрос: <i><?= $search; ?></i></h3>
                            <form action="" method="get">
                                <input type="hidden" name="search" value="<?= $search; ?>">
                                <div class="btn-group btn-group-lg" role="group" aria-label="Sorting">
                                    <button type="submit" name="do_new" class="btn btn-secondary">Новые</button>
                                    <button type="submit" name="do_old" class="btn btn-secondary">Старые</button>
                                    <button type="submit" name="do_price" class="btn btn-secondary">Цена</button>
                                </div>
                            </form>
                        </center>
                        <hr>
                        <div class="row">
                            <?php foreach ($rows as $row) : ?>
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100">
                                        <a href="/details.php?id=<?= $row['id']; ?>"><img class="card-img-top mt-2" src="/img/<?= show_img($row['img']); ?>" alt=""></a>
                                        <div class="card-body">
                                            <hr>
                                            <h4 class="card-title">
                                                <a href="/category.php?name=<?= $row['name_cat']; ?>"><?= $row['name_cat']; ?></a>
                                            </h4>
                                            <hr>
                                            <h4 class="card-title">
                                                <a href="/details.php?id=<?= $row['id']; ?>"><?= $row['name']; ?></a>
                                            </h4>
                                            <hr>
                                            <h5><?= $row['price']; ?> рублей</h5>
                                            <p class="card-text"><?= $row['description']; ?></p>
                                            <a href="/cart.php/?product_id=<?= $row['id']; ?>" class="btn btn-success">Добавить в корзину</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                </div>
                <div class="col-md-3 order-md-2 mb-4">
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
                    <form class="card p-2" action="/discounts.php/?search=<?= $search; ?>&page=<?= $page; ?>" method="post">
                        <div class="input-group">
                            <input type="text" name="promo" class="form-control" placeholder="Промокод">
                            <div class="input-group-append">
                                <button type="submit" name="do_search_promo" class="btn btn-secondary">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <nav aria-label="Search results pages">
                <ul class="pagination">
                    <li class="page-item"><a class="page-link" href="<?= "http://shop/search.php/?search=$search&page=$prev&do_sorting=$do_sorting"; ?>">Назад</a></li>
                    <?= Pagination($length, $page, $search); ?>
                    <li class="page-item"><a class="page-link" href="<?= "http://shop/search.php/?search=$search&page=$next&do_sorting=$do_sorting"; ?>">Дальше</a></li>
                </ul>
            </nav>
        <?php endif; ?>
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