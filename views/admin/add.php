<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 1:52
 */

?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h1>Add admin</h1>

<?php //if ($error_api > 0) {
//    foreach ($error_api as $error) {
//        echo "<p style='color: red;'>$error</p>";
//    }
//}
?>

<?php if ($flag_ok) : ?>

    <h3 style="color: green";>Новий адмін успішно доданий!</h3>

    <div>
        <p><a href="/admin/add">Добавити ще одного</a></p>
        <p><a href="/">На головну</a></p>
    </div>

<?php else: ?>

<form action="" method="post">
    <?php if (isset($auth_error["first_name_length"] )) echo "<p style='color: red;'>Імя повинно містити щонайменше 3 символи</p>"?>
    <p>
        <label for="name">Ім'я: </label>
        <input type="text" id="name" name="first_name" value="<?php if (isset($_POST['first_name'])) echo $_POST["first_name"]; ?>">
    </p>
    <?php if (isset($auth_error["last_name_length"] )) echo "<p style='color: red;'>Прізвище повинно містити щонайменше 3 символи</p>"?>
    <p>
        <label for="last_name">Прізвище: </label>
        <input type="text" id="last_name" name="last_name" value="<?php if (isset($_POST['last_name'])) echo $_POST["last_name"] ?>">
    </p>
    <?php if (isset($auth_error["email"] )) echo "<p style='color: red;'>Введіть коректний email</p>"?>
    <p>
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" value="<?php if (isset($_POST['email'])) echo $_POST["email"] ?>">
    </p>
    <?php if (isset($auth_error["password_length"] )) echo "<p style='color: red;'>Пароль повинен містити щонайменше 3 символи</p>"?>
    <p>
        <label for="password">Пароль: </label>
        <input type="password" id="password" name="password">
    </p>
    <?php if (isset($auth_error["repeat_password"] )) echo "<p style='color: red;'>Паролі не співпадають</p>"?>
    <p>
        <label for="repeat_password">Повторіть пароль: </label>
        <input type="password" id="repeat_password" name="repeat_password">
    </p>

    <input type="submit" value="Send" name="submit">
    <br>

    <div style="clear: both"></div>
</form>


<?php endif; ?>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>

