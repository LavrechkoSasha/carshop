<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 03.04.2017
 * Time: 21:22
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>


<?php if (count($my_product) > 0) :?>
    <?php foreach ($my_product as $product): ?>
        <?php $product['id'] = $product ?>

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
        <a href="<?php echo "/products/{$product['_id']['$id']}"; ?>">Перегляд</a>
        <hr>
    <?php endforeach; ?>
<?php else: ?>
    <h2 style="color:red;">Нажаль товарів ще нема!</h2>
<?php endif;?>


<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
