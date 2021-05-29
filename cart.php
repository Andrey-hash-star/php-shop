<?php

require_once('dbConnection.php');
require_once('function.php');

if (isset($_GET['delete_id']) && isset($_SESSION['cart_list'])) {
    foreach ($_SESSION['cart_list'] as $key => $value) {
        if ($value['id'] == $_GET['delete_id']) {
            unset($_SESSION['cart_list'][$key]);
        }
    }
}

if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {

    function get_product_by_id($id)
    {
        global $pdo;

        $table1 = "products";
        $table2 = "categories";

        $sql = "SELECT categories.name AS name_cat, products.id, products.name, products.description, products.price, products.discount, products.img  FROM $table1 INNER JOIN $table2 ON products.id_cat = categories.id WHERE products.id = :id";

        $stmt = $pdo->prepare($sql);
        $params = [':id' => $id];
        $stmt->execute($params);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    $current_added_product = get_product_by_id($_GET['product_id']);

    if (!empty($current_added_product)) {

        if (!isset($_SESSION['cart_list'])) {
            $_SESSION['cart_list'][] = $current_added_product;
        }


        $course_check = false;

        if (isset($_SESSION['cart_list'])) {
            foreach ($_SESSION['cart_list'] as $value) {
                if ($value['id'] == $current_added_product['id']) {
                    $course_check = true;
                }
            }
        }


        if (!$course_check) {
            $_SESSION['cart_list'][] = $current_added_product;
        }
    } else {
        echo "Ошибка 404!";
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
    <title>Корзина</title>
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
                <center>Корзина</center>
            </h1>
            <?php if (isset($_SESSION['discount'])) : ?>
                <h4 class="mb-5">
                    <center>Общая стоимость товаров в корзине: <?= ceil(array_sum($arr) - (array_sum($arr) * $_SESSION['discount']['discount'] / 100)); ?> рублей</center>
                </h4>
                <h5 class="mb-5">
                    <center>Ваша скидка по промокоду "<?= $_SESSION['discount']['name']; ?>" равна: <?= $_SESSION['discount']['discount']; ?> % <a class="btn btn-danger" role="button" href="/del_discounts.php">X</a></center>
                </h5>
            <?php else : ?>
                <h4 class="mb-3">
                    <center>Общая стоимость товаров в корзине: <?= array_sum($arr); ?> рублей</center>
                </h4>
            <?php endif; ?>
            <?php if (isset($_SESSION['cart_list']) && count($_SESSION['cart_list']) != 0) : ?>
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">Название</th>
                            <th scope="col">Категория</th>
                            <th scope="col">Цена</th>
                            <?php if (isset($_SESSION['discount'])) : ?>
                                <th scope="col">Скидка</th>
                            <?php endif; ?>
                            <th scope="col">Удалить</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart_list'] as $product_cart) : ?>
                            <tr>
                                <th scope="row"><?= $product_cart['id']; ?></th>
                                <td><a href="/details.php/?id=<?= $product_cart['id'] ?>" style="color:black; text-decoration:none;"><?= $product_cart['name']; ?></a></td>
                                <td><a href="/category.php/?name=<?= $product_cart['name_cat'] ?>" style="color:black; text-decoration:none;"><?= $product_cart['name_cat']; ?></a></td>
                                <?php if (isset($_SESSION['discount'])) : ?>
                                    <td><?= ceil($product_cart['price'] - ($product_cart['price'] * $_SESSION['discount']['discount'] / 100)); ?> рублей</td>
                                <?php else : ?>
                                    <td><?= $product_cart['price']; ?> рублей</td>
                                <?php endif; ?>
                                <?php if (isset($_SESSION['discount'])) : ?>
                                    <td><?= $_SESSION['discount']['discount']; ?> % </td>
                                <?php endif; ?>
                                <td><a class="btn btn-danger" role="button" href="cart.php?delete_id=<?= $product_cart['id']; ?>">X</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>
                    <h3>
                        <center>Ваша корзина пуста!</center>
                    </h3>
                </p>
            <?php endif; ?>
            <?php if (isset($_SESSION['cart_list']) && count($_SESSION['cart_list']) != 0) : ?>
                <a class="btn btn-success btn-lg mt-5 mb-3" role="button" href="/">Продолжить покупки</a>
                <a class="btn btn-success btn-lg mt-5 mb-3" role="button" href="#">Оформить заказ</a>
            <?php else : ?>
                <a class="btn btn-success btn-lg btn-block mt-5 mb-5" role="button" href="/">Вернуться и продолжить покупки</a>
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