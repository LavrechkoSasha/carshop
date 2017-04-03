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
    'users' => 'users/login',

    'api/all_products' => 'api/allProducts',
    'api/view_product/([a-zA-z0-9]+)' => 'api/ViewProduct/$1',

    'api/add_new_user' => 'api/addNewUser',
    'api/login' => 'api/login',
    'api/add_new_car' => 'api/addNewCar',
    'api/edit_car' => 'api/editCar',
    'api' => 'api/index',

    'products/index' => 'products/index',
    'products/add' => 'products/add',
    'products/edit/([a-zA-z0-9]+)' => 'products/edit/$1',
    'products/([a-zA-z0-9]+)' => 'products/view/$1',
    'products' => 'products/index',

    '' => 'index/index'
];