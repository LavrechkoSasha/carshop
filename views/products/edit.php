<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 03.04.2017
 * Time: 2:49
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>


<h1>EdIt</h1>


<h3>Редагування автомобіля з id:  <?php echo $id; ?></h3>

<?php if (count($api_error) == 0): ?>

<form action="" method="post">
    <?php if(isset($validate_errors['change_not_found'])) echo "<p style='color: red;'>{$validate_errors['change_not_found']}</p>"; ?>
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

<?php
else:
    foreach ($api_error as $error):
        ?>
        <h3 style="color: red;"><?php echo $error; ?></h3>
        <?php
    endforeach;
endif;

?>

<!--<img src="https://upload.wikimedia.org/wikipedia/commons/3/3f/Fronalpstock_big.jpg" alt="big">-->

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
