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
    <a href="/">На головну</a>
    <span>||</span>
    <?php if (isset($_SESSION['user'])) : ?>
<!--        <a href="/user/cabinet">Особистий кабінет</a>
        <span>||</span>-->
        <a href="/users/logout">Вихід</a>
        <hr>
        <p>Ви ввійшли як <b style="font-size: large;"><u> <?php echo $_SESSION['user']['first_name']; ?> </u></b></p>
        <hr>
    <?php else: ?>
        <a href="/users/login">Вхід</a>
        <span>||</span>
        <a href="/users/add">Реєстрація</a>
    <?php endif; ?>
</header>

