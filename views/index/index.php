<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 02.04.2017
 * Time: 15:03
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<?php if (count($all_product) > 0) :?>
    <?php foreach ($all_product as $product): ?>
        <div>
            <div style="float:left; width: 100px;">Марка</div>
            <div style="float:left; width: 300px;"><?php echo $product['brand']; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div>
            <div style="float:left; width: 100px;">Модель</div>
            <div style="float:left; width: 300px;"><?php echo $product['model']; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div>
            <div style="float:left; width: 100px;">Рік</div>
            <div style="float:left; width: 300px;"><?php echo $product['year']; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div>
            <div style="float:left; width: 100px;">Колір</div>
            <div style="float:left; width: 300px;"><?php echo $product['color']; ?></div>
            <div style="clear: both;"></div>
        </div>

        <div>
            <div style="float:left; width: 100px;"><h3>Ціна</h3></div>
            <div style="float:left; width: 300px;"><h3 style="color: green;"><?php echo $product['price']; ?></h3></div>
            <div style="clear: both;"></div>
        </div>

        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <h2 style="color:red;">Нажаль товарів ще нема!</h2>
<?php endif;?>
<!--
    <p><a href="/users/add">add</a></p>
    <p><a href="/users/login">login</a></p>
    <p><a href="/users/logout">logout</a></p>-->
<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
