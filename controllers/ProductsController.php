<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 15:23
 */
class ProductsController extends AppController
{
    public function actionIndex()
    {
        $all_product = [];
        $title = "Каталог товарів";


        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/get_products");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $all_product = json_decode($result, true);
//            var_dump($all_product);

        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }
        require_once ROOT.'/views/products/index.php';
        return true;
    }

    public function actionUserProducts()
    {
        if (! isset($_SESSION['user']['id'])) {
            header("Location: /users/login");
        }

        $all_product = [];
        $title = "Каталог товарів";

        $user_id = $_SESSION['user']['id'];

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/get_products/$user_id");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $my_product = json_decode($result, true);
            var_dump($my_product);

        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }
        require_once ROOT.'/views/products/myproducts.php';
        return true;
    }

    public function actionView($parameters)
    {
        $id = $parameters[0];
        $api_error = [];

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/view_product/$id");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $result_arr = json_decode($result, true);

            if (!isset($result_arr['_id'])) {
                $api_error = $result_arr;

                $title = "Помилка :(";
            } else {
                $car_info = $result_arr;

                $title = "{$car_info['brand']} {$car_info['model']}  {$car_info['year']}";
            }
        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }

        require_once ROOT.'/views/products/view.php';
        return true;
    }

    public function actionAdd()
    {
        if (! isset($_SESSION['user']['id'])) {
            header("Location: /users/login");
        }

        $title = "Додавання нового автомобіля";

        $brand = "";
        $model = "";
        $year = "";
        $color = "";
        $price = "";
        $validate_errors = [];
        $api_error = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $brand = $_POST['brand'];
            $model = $_POST['model'];
            $year = $_POST['year'];
            $color = $_POST['color'];
            $price = $_POST['price'];

            $new_car = [];

            if (isset($_POST['submit'])) {

                if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $brand)) {
                    $validate_errors['brand'] = "Введіть корректну марку автомобіля!";
                }
                if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $model)) {
                    $validate_errors['model'] = "Введіть корректну модель автомобіля!";
                }
                if (! preg_match('~[a-zA-z0-9_ -]{4,20}~i', $year)) {
                    $validate_errors['year'] = "Введіть корректний рік виготовлення автомобіля!";
                }
                if (! preg_match('~[a-zA-z0-9_-]{3,64}~i', $color)) {
                    $validate_errors['color'] = "Введіть корректний колір автомобіля!";
                }
                if (! preg_match('~[a-zA-z0-9_-]+~', $price)) {
                    $validate_errors['price'] = "Введіть корректну ціну автомобіля!";
                }

                if (count($validate_errors) == 0) {

                    $new_car['brand'] = $brand;
                    $new_car['model'] = $model;
                    $new_car['year'] = $year;
                    $new_car['color'] = $color;
                    $new_car['price'] = $price;
                    $new_car['author_id'] = $_SESSION['user']['id'];
                    $new_car['created'] = time();

                    $car = json_encode($new_car);

                    if( $curl = curl_init() ) {
                        curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/add_new_car/');
                        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                        curl_setopt($curl, CURLOPT_POST, true);
                        curl_setopt($curl, CURLOPT_POSTFIELDS, "car=$car");
                        $result = curl_exec($curl);
                        curl_close($curl);

                        $result_arr = json_decode($result, true);

                        if (!isset($result_arr['id'])) {
                            $api_error = $result_arr;
                        } else {
                            $link = '/products/'.$result_arr['id'];
//                            var_dump($link);
                            header("Location: $link");
                        }
                    } else {
                        $api_error['curl'] = 'Не вдалося звязатись з API!';
                    }
                }
            }
        }

        require_once ROOT.'/views/products/add.php';
        return true;

    }

    public function actionEdit($id) {

        if (! isset($_SESSION['user']['id'])) {
            header("Location: /users/login");
        }

        $id = $id[0];
        $api_error = [];
        $validate_errors = [];
        $edit_fields = [];

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/view_product/$id");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $result_arr = json_decode($result, true);

            if (!isset($result_arr['_id'])) {
                $api_error = $result_arr;
                $title = "Помилка :(";
            } else {
                $car_info = $result_arr;
            }
        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }

        if (isset($car_info)) {

            $brand = $car_info['brand'];
            $model = $car_info['model'];
            $year = $car_info['year'];
            $color = $car_info['color'];
            $price = $car_info['price'];
            $author_id = $car_info['author_id'];
            $created = date("y.m.d H:i:s", $car_info['created']);
            if ($car_info['author_id'] == $_SESSION['user']['id']) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['brand'])) {
                        $validate_errors['brand'] = "Введіть корректну марку автомобіля!";
                    }
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['model'])) {
                        $validate_errors['model'] = "Введіть корректну модель автомобіля!";
                    }
                    if (! preg_match('~[a-zA-z0-9_ -]{4,20}~i', $_POST['year'])) {
                        $validate_errors['year'] = "Введіть корректний рік виготовлення автомобіля!";
                    }
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['color'])) {
                        $validate_errors['color'] = "Введіть корректний колір автомобіля!";
                    }
                    if (! preg_match('~[a-zA-z0-9_ -]+~', $_POST['price'])) {
                        $validate_errors['price'] = "Введіть корректну ціну автомобіля!";
                    }


                    if (count($validate_errors) == 0) {
                        foreach ($_POST as $field_key => $field_value) {
                            if ($field_key == 'submit') continue;

                            if ($car_info[$field_key] != $_POST[$field_key]){
                                $edit_fields [$field_key] = $_POST[$field_key];
                            }
                        }

                        if (count($edit_fields) > 0) {
                            $edit_fields = json_encode($edit_fields);
                            if( $curl = curl_init() ) {
                                curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/edit_car/$id");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                                curl_setopt($curl, CURLOPT_POST, TRUE);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, "edit_fields=$edit_fields" );
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                $result = curl_exec($curl);
                                curl_close($curl);

                                $result_arr = json_decode($result, true);

                                if (!isset($result_arr['_id'])) {
                                    $api_error = $result_arr;
                                } else {
                                    $brand = $result_arr['brand'];
                                    $model = $result_arr['model'];
                                    $year = $result_arr['year'];
                                    $color = $result_arr['color'];
                                    $price = $result_arr['price'];
                                    $author_id = $result_arr['author_id'];
                                    $created = date("y.m.d H:i:s", $result_arr['created']);
                                }
                            }
                        } else {
                            $validate_errors['change_not_found'] = 'Не внесено ніяких змін';
                        }
                    }
                }
            } else {
                header("Location: /products/$id");
            }
        }

        $title = "Edit";

        require_once ROOT.'/views/products/edit.php';
        return true;
    }
}