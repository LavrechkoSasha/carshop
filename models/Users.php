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
        $mongo->close();
        return $result;
    }

    public static function emailExist($user) {

        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $result = $collection_users->count(['email' => $user['email']]);
        $mongo->close();
        return $result;
    }

    public static function socialIdentityExist($user)
    {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $result = $collection_users->count(['identity' => $user['identity']]);

        $mongo->close();
        return $result;
    }

    /**
     * При успішній авторизації повертає масив з даними про користувача
     * в іншому випадку повертає null
     *
     * @param $user
     * @return array|null
     */
    public static function userLogin($user) {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

//        var_dump($user);

        if (isset($user['identity'])) {
            $result = $collection_users->findOne(['identity' => $user['identity']]);
        } else {
            $result = $collection_users->findOne(['email' => $user['email'], 'password' => $user['password']]);
        }
        $mongo->close();
        return $result;
    }

    public static function checkUserIdExist ($user_id)
    {
        $mongo = new MongoClient();
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $result = $collection_users->count(['identity' => $user_id]);
        $mongo->close();
        return $result;
    }
}

