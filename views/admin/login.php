<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 2:42
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
</form>



<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
