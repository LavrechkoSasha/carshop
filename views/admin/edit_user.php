<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 6:48
 */
?>
<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h3>Редагування користувача з id:  <?php echo $id; ?></h3>

<?php if (count($api_error) == 0): ?>

    <form action="" method="post">
        <?php if(isset($validate_errors['change_not_found'])) echo "<p style='color: red;'>{$validate_errors['change_not_found']}</p>"; ?>
        <p>
            <?php if (isset($validate_errors['first_name'])) echo "<p style='color: red;'>{$validate_errors['first_name']}</p>" ?>
            <label for="name">Ім'я:  </label>
            <input type="text" name="first_name" value="<?php echo $first_name; ?>">
        </p>
        <p>
            <?php if (isset($validate_errors['last_name'])) echo "<p style='color: red;'>{$validate_errors['last_name']}</p>" ?>
            <label for="last_name">Прізвище: </label>
            <input type="text" name="last_name" value="<?php echo $last_name; ?>">
        </p>

        <p>
            <?php if (isset($validate_errors['email'])) echo "<p style='color: red;'>{$validate_errors['email']}</p>" ?>
            <label for="email">Email: </label>
            <input type="email" name="email" value="<?php echo $email; ?>">
        </p>

        <?php if (isset($password)): ?>

            <p>
                <?php if (isset($validate_errors['password'])) echo "<p style='color: red;'>{$validate_errors['password']}</p>" ?>
                <label for="name">Пароль: </label>
                <input type="text" name="password" value="<?php echo $password; ?>">
            </p>

        <?php endif; ?>

        <p>
            <?php if (isset($validate_errors['price'])) echo "<p style='color: red;'>{$validate_errors['price']}</p>" ?>
            <label for="name">Підтвердження: </label>

            <select name="is_verification" id="is_verification">
                <option value="0" <?php if ($is_verification == 0) echo "selected"; ?> >Ні</option>
                <option value="1" <?php if ($is_verification == 1) echo "selected"; ?> >Так</option>
            </select>
        </p>
        <p>
            <!--        <label for="name">Ціна: </label>-->
            <input type="submit" name="submit" value="Додати">
        </p>


    </form>

    <?php
else:
    foreach ($api_error as $error):
        ?>
        <h3 style="color: red;"><?php echo $error; ?></h3>
        <?php
    endforeach;
endif;

?>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
