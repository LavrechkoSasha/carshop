<?php
/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:07
 */

return [
    'users/add' => 'users/add',
    'users/login' => 'users/login',
    'users/logout' => 'users/logout',

    'api/all_products' => 'api/allProducts',
    'api/add_new_user' => 'api/addNewUser',
    'api/login' => 'api/login',

    'api' => 'api/index',


    'users' => 'users/index',
    'products/([a-zA-z0-9])' => 'products/view/$1',
    'products' => 'products/index',
    'products/index' => 'products/index',
    '' => 'index/index'
];