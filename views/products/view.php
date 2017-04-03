<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 02.04.2017
 * Time: 23:39
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h1><?php echo "View"; ?></h1>

<h3>Перегляд автомобіля з id:  <?php echo $parameters[0]; ?></h3>

<?php if (count($api_error) == 0): ?>

<div>
    <div style="float:left; width: 100px;">Марка</div>
    <div style="float:left; width: 300px;"><?php echo $car_info['brand']; ?></div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float:left; width: 100px;">Модель</div>
    <div style="float:left; width: 300px;"><?php echo $car_info['model']; ?></div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float:left; width: 100px;">Рік</div>
    <div style="float:left; width: 300px;"><?php echo $car_info['year']; ?></div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float:left; width: 100px;">Колір</div>
    <div style="float:left; width: 300px;"><?php echo $car_info['color']; ?></div>
    <div style="clear: both;"></div>
</div>

<div>
    <div style="float:left; width: 100px;"><h3>Ціна</h3></div>
    <div style="float:left; width: 300px;"><h3 style="color: green;"><?php echo $car_info['price']; ?></h3></div>
    <div style="clear: both;"></div>
</div>

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
