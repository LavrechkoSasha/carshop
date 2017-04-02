<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 02.04.2017
 * Time: 4:55
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h1>Login</h1>

<form action="" method="post">
    <?php if (isset($validate_errors['user_does_not_exist'])) echo "<p style='color: red;'>Невірний логін або пароль</p>"?>
    <p>
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST["email"] ?>">
    </p>
<!--    --><?php //if (isset($login_error["password_length"] )) echo "<p style='color: red;'>Пароль повинен містити щонайменше 3 символи</p>"?>
    <p>
        <label for="password">Пароль: </label>
        <input type="password" id="password" name="password">
    </p>

    <input type="submit" value="Send" name="submit">
    <br>

    <div style="clear: both"></div>
    <p><div id="uLogin" data-ulogin="display=small;theme=classic;fields=first_name,last_name,email;providers=vkontakte,odnoklassniki,mailru,facebook;hidden=other;redirect_uri=http%3A%2F%2Fcarshop%2Fusers%2Fadd%2F;mobilebuttons=0;"></div></p>
</form>

<script src="//ulogin.ru/js/ulogin.js"></script>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>