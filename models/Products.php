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
        $collection_products = $mongo->selectDB('carshop')->selectCollection('products');
        $all_products = $collection_products->find();
        $result = json_encode(iterator_to_array($all_products));
        $mongo->close();

        return $result;
    }

    public static function checkProductExist($id) {

        $mongo = new MongoClient();
        $collection_products = $mongo->selectDB('carshop')->selectCollection('products');

        $count_car = $collection_products->count(["_id" => new MongoId($id)]);
        $mongo->close();

        return $count_car;
    }

    public static function getProduct($id) {
        $mongo = new MongoClient();
        $collection_products = $mongo->selectDB('carshop')->selectCollection('products');

        $car = $collection_products->findOne(["_id" => new MongoId($id)]);
        $mongo->close();
        return $car;
    }

    public static function updateProduct ($id, $update_fields) {
        $mongo = new MongoClient();
        $collection_products = $mongo->selectDB('carshop')->selectCollection('products');

        $option = ["upsert" => false, "multiple" => false];

/*        var_dump($id);
        var_dump($update_fields);*/

        $newDoc = ['$set' => $update_fields];

        $result_update = $collection_products->update(["_id" => new MongoId($id)], $newDoc, $option);

        if ($result_update['ok']) {
            $car = self::getProduct($id);
            $car['_id'] = $car['_id']->{'$id'};

            $mongo->close();
            return $car;
        } else {
            $mongo->close();
            $update_error['error'] = "Не вдалось обновити дані автомобіля!";
            return $update_error;
        }



    }

    /**
     * Повертає масив який містить один елемент.
     *
     * У разі успішного додавання товару(автомобіля) буде повернено асоціативний масив з унікальним
     * значенням щойно доданого товару (id).
     *
     * У випадку, якщо при записі в базу відбулась помилка, буде повернуто асоціативний масив,
     * який повідомляє що сталась помилка.
     *
     * @param $car
     * @return array
     */
    public static function addNewCar($car)
    {
        $mongo = new MongoClient(); // соединяемся с сервером
        $collection_products = $mongo->selectDB('carshop')->selectCollection('products');
        $result = $collection_products->insert($car);
        $mongo->close();
        if ($result['ok']) {
            $car_id['id'] = $car['_id']->{'$id'};
            return $car_id;
        } else {
            $insert_error['error'] = "Не вдалось добавити новий автомобіль!";
            return $insert_error;
        }
    }
}