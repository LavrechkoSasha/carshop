<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 15:56
 */
class Products
{
    public static function getAllProducts()
    {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');
        $all_users = $collection_users->find();
        $result = json_encode(iterator_to_array($all_users));
        $mongo->close();

        return $result;
    }
}