<?php

require_once('dbConnection.php');
require_once('function.php');

if (isset($_POST['promo']) && isset($_POST['do_index_promo'])) {

    $promo = trim(htmlspecialchars($_POST['promo']));
    $table = 'discounts';
    $page = $_GET['page'];

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE name = ? ");
    $params = [$promo];
    $stmt->execute($params);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$discount) {  
    die(header("Location:/?page=$page&error_promo=1"));   
    }

    $_SESSION['discount'] = $discount;
    header("Location:/?page=$page&success_promo=1");

} elseif (isset($_POST['promo']) && isset($_POST['do_category_promo'])) {

    $promo = trim(htmlspecialchars($_POST['promo']));
    $table = 'discounts';
    $name = $_GET['name'];
    $page = $_GET['page'];
    
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE name = ? ");
    $params = [$promo];
    $stmt->execute($params);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$discount) {
        die(header("Location:/category.php/?name=$name&page=$page&error_promo=1"));
    }

    $_SESSION['discount'] = $discount;
    header("Location:/category.php/?name=$name&page=$page&success_promo=1");

} elseif (isset($_POST['promo']) && isset($_POST['do_details_promo'])) {

    $promo = trim(htmlspecialchars($_POST['promo']));
    $table = 'discounts';
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE name = ? ");
    $params = [$promo];
    $stmt->execute($params);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$discount) {
        die(header("Location:/details.php?id=$id&error_promo=1"));
    }

    $_SESSION['discount'] = $discount;
    header("Location:/details.php?id=$id&success_promo=1");
    
} elseif (isset($_POST['promo']) && isset($_POST['do_search_promo'])) {

    $promo = trim(htmlspecialchars($_POST['promo']));
    $table = 'discounts';
    $search = $_GET['search'];
    $page = $_GET['page'];

    $stmt = $pdo->prepare("SELECT * FROM $table WHERE name = ? ");
    $params = [$promo];
    $stmt->execute($params);
    $discount = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$discount) {
        die(header("Location:/search.php/?search=$search&page=$page&error_promo=1"));
    }

    $_SESSION['discount'] = $discount;
    header("Location:/search.php/?search=$search&page=$page&success_promo=1");
    
} else {
    header('Location:/?error_promo=1');
}







