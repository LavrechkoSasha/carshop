<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 1:51
 */
?>

<?php  require_once (ROOT . '/views/layouts/header.php'); ?>

<h2>Users</h2>

<div>
    <div style="float: left; width: 49%;">
        <h3>Не підверджені</h3>
        <?php
        if(count($check_users > 0)) :
            foreach ($check_users as $user_id => $user_data) :
        ?>

                <p><a href="/admin/edit_user/<?php echo $user_id; ?>"><?php echo "{$user_data['first_name']} {$user_data['last_name']} || {$user_data['email']}" ?></a></p>

        <?php
            endforeach;
        endif;
        ?>
    </div>
    <div style="float: left; width: 49%;">
        <h3>Підверджені</h3>
        <?php
        if(count($no_check_users > 0)) :
            foreach ($no_check_users as $user_id => $user_data) :
        ?>

                <p><a href="/admin/edit_user/<?php echo $user_id; ?>"><?php echo "{$user_data['first_name']} {$user_data['last_name']} || {$user_data['email']}" ?></a></p>

        <?php
            endforeach;
        endif;
        ?>
    </div>
    <div style="clear: both";></div>
</div>

<?php  require_once (ROOT . '/views/layouts/footer.php'); ?>
