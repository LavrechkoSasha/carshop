<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 02.04.2017
 * Time: 16:05
 */
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $title; ?></title>
</head>
<body>

<header>

<!--   Завжди-->
    <a href="/">На головну</a> <span>||</span>
    <a href="/products/index">Каталог</a> <span>||</span>

    <?php if (! isset($_SESSION['user']['id'])) : ?>
<!--    користувач не залогінений-->
    <a href="/users/add">Реєстрація</a> <span>||</span>
    <a href="/users/login">Вхід</a>
    <?php else: ?>
<!--    користувач залогінений-->
        <?php if (! isset($_SESSION['user']['is_admin'])) : ?>
            <a href="/products/my_products">Мої товари</a> <span>||</span>
            <a href="/products/add">Додати товар</a> <span>||</span>
        <?php else: ?>
            <a href="/admin">Адмінка</a> <span>||</span>
        <?php endif; ?>


    <a href="/users/logout">Вихід</a>
    <?php endif; ?>
<!--    if (! isset($_SESSION['user']['is_admin'])) header("Location: /products");-->
</header>
<hr>

<?php //var_dump($_SESSION); ?>

