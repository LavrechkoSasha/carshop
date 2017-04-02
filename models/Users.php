<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 19:01
 */
class Users
{
    public static function addUser($user)
    {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');
        $result = $collection_users->insert($user);

        return $result;
    }

    public static function emailExist($user) {

        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $result = $collection_users->count(['email' => $user['email']]);

        return $result;
    }

    public static function socialIdentityExist($user)
    {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $result = $collection_users->count(['identity' => $user['identity']]);
        return $result;
    }

    public static function checkLoginExists ($user) {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        if (isset($user['identity'])) {
            $result = $collection_users->findOne(['identity' => $user['identity']]);
        } else {
            $result = $collection_users->findOne(['email' => $user['email']]);
        }

        return $result;
    }
}

