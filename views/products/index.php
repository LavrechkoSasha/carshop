<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 18:35
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

    <?php foreach ($allProducts as $user): ?>
        <div>
            <p><?php echo $user['first_name']; ?></p>
        </div>
    <?php endforeach;?>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>