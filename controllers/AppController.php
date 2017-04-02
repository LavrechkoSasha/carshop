<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:32
 */
class AppController
{
    public $a = 'var with app controller';

    public function loginUser ($user_data)
    {
        if (isset($user_data['token'])) {
            $_SESSION['user']['token'] = $user_data['token'];
        }

        $_SESSION['user']['id'] = $user_data['_id']['$id'];
        $_SESSION['user']['first_name'] = $user_data['first_name'];
        $_SESSION['user']['last_name'] = $user_data['last_name'];
        $_SESSION['user']['email'] = $user_data['email'];

        header('Location: /');
    }
}