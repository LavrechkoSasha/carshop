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

    'api/get_products' => 'api/getProducts',
    'api/view_product/([a-zA-z0-9]+)' => 'api/ViewProduct/$1',

    'api/add_admin_user' => 'api/addAdminUser',
    'api/login_admin_user' => 'api/loginAdminUser',
    'api/users_list' => 'api/usersList',
    'api/edit_user' => 'api/editUser',
    'api/view_user/([a-zA-z0-9]+)' => 'api/viewUser/$1',

    'api/add_new_user' => 'api/addNewUser',
    'api/login' => 'api/login',
    'api/add_new_car' => 'api/addNewCar',
    'api/edit_car' => 'api/editCar',

    'admin/add' => 'admin/add',
    'admin/login' => 'admin/login',
    'admin/edit_user' => 'admin/editUser',
//    'admin/users/([a-zA-z0-9]+)' => 'admin/editUser/$1',
    'admin/users' => 'admin/usersList',


    'products/index' => 'products/index',
    'products/my_products' => 'products/userProducts',
    'products/add' => 'products/add',
    'products/edit/([a-zA-z0-9]+)' => 'products/edit/$1',
    'products/([a-zA-z0-9]+)' => 'products/view/$1',


    'products' => 'products/index',
    'admin' => 'admin/index',
    'api' => 'api/index',
    'users' => 'users/login',
    '' => 'index/index'
];