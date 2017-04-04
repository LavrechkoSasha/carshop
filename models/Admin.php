<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 2:04
 */
class Admin
{
    public static function adminEmailExist($user) {

        $mongo = new MongoClient();
        $collection_admins = $mongo->selectDB('carshop')->selectCollection('admins');

        $result = $collection_admins->count(['email' => $user['email']]);
        $mongo->close();
        return $result;
    }

    public static function addAdminUser($user_data) {
        $mongo = new MongoClient();
        $collection_admins = $mongo->selectDB('carshop')->selectCollection('admins');
        $result = $collection_admins->insert($user_data);
        $mongo->close();
        return $result;
    }

    public static function adminLogin($user) {
        $mongo = new MongoClient();
        $collection_admins = $mongo->selectDB('carshop')->selectCollection('admins');

        $result = $collection_admins->findOne(['email' => $user['email'], 'password' => $user['password']]);

        $mongo->close();
        return $result;
    }


    public static function getUsersList($filter)
    {
        $mongo = new MongoClient();
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');
        $users = $collection_users->find($filter);
        $result = iterator_to_array($users);
        $mongo->close();

        return $result;
    }

    public static function checkUserExist($id)
    {
        $mongo = new MongoClient();
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $count_user = $collection_users->count(["_id" => new MongoId($id)]);
        $mongo->close();

        return $count_user;
    }

    public static function getUser($id)
    {
        $mongo = new MongoClient();
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $user = $collection_users->findOne(["_id" => new MongoId($id)]);
        $mongo->close();
        return $user;
    }

    public static function updateUser ($id, $update_fields) {
        $mongo = new MongoClient();
        $collection_users = $mongo->selectDB('carshop')->selectCollection('users');

        $option = ["upsert" => false, "multiple" => false];

        $newDoc = ['$set' => $update_fields];

        $result_update = $collection_users->update(["_id" => new MongoId($id)], $newDoc, $option);

        if ($result_update['ok']) {
            $user = self::getUser($id);
            $user['_id'] = $user['_id']->{'$id'};

            $mongo->close();
            return $user;
        } else {
            $mongo->close();
            $update_error['error'] = "Не вдалось обновити дані користувача!";
            return $update_error;
        }
    }

}