<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 02.04.2017
 * Time: 17:36
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h1>Додавання нового товару</h1>

<?php
if (count($api_error) > 0):
    foreach ($api_error as $error) :
?>
    <p style="color: red;"><?php echo $error; ?></p>
<?php
    endforeach;
endif;
?>

<form action="" method="post">
    <p>
        <?php if (isset($validate_errors['brand'])) echo "<p style='color: red;'>{$validate_errors['brand']}</p>" ?>
        <label for="name">Марка автомобіля: </label>
        <input type="text" name="brand" value="<?php echo $brand; ?>">
    </p>
    <p>
        <?php if (isset($validate_errors['model'])) echo "<p style='color: red;'>{$validate_errors['model']}</p>" ?>
        <label for="name">Модель автомобіля: </label>
        <input type="text" name="model" value="<?php echo $model; ?>">
    </p>

    <p>
        <?php if (isset($validate_errors['year'])) echo "<p style='color: red;'>{$validate_errors['year']}</p>" ?>
        <label for="name">Рік: </label>
        <input type="text" name="year" value="<?php echo $year; ?>">
    </p>

    <p>
        <?php if (isset($validate_errors['color'])) echo "<p style='color: red;'>{$validate_errors['color']}</p>" ?>
        <label for="name">Колір: </label>
        <input type="text" name="color" value="<?php echo $color; ?>">
    </p>

    <p>
        <?php if (isset($validate_errors['price'])) echo "<p style='color: red;'>{$validate_errors['price']}</p>" ?>
        <label for="name">Ціна: </label>
        <input type="number" name="price" value="<?php echo $price; ?>">
    </p>
    <p>
<!--        <label for="name">Ціна: </label>-->
        <input type="submit" name="submit" value="Додати">
    </p>


</form>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
