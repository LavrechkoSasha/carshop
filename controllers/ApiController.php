<?php
require_once ROOT.'/models/Users.php';
require_once ROOT.'/models/Products.php';

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:31
 */
class ApiController
{
    public function actionGetProducts($user_id = null)
    {
        if (!$user_id) {
            $result =  json_encode(Products::getAllProducts());
        } else {

            $result =  json_encode(Products::getUserProducts($user_id[0]));
        }
        echo $result;
    }

    public function actionViewProduct($id)
    {
        require_once ROOT.'/models/Products.php';

        if (preg_match('~^[a-f0-9]{24}$~', $id[0])) {
            if (Products::checkProductExist($id[0])) {
                $result = Products::getProduct($id[0]);
                echo json_encode($result);
            } else {
                $product_error['is_not_exist'] = 'Товару з таким id не існує!';
                echo json_encode($product_error);
            }
        } else {
            $product_error['is_not_exist'] = 'Товару з таким id не існує!';
            echo json_encode($product_error);
        }
    }

    public function actionAddNewUser()
    {
        require_once ROOT.'/models/Users.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            function checkDataUser ($user) {
                global $auth_error;

                if (!(isset($user['first_name']) && strlen($user['first_name'])>3)) {
                    $auth_error['first_name_length'] = "Імя повинно містити щонайменше 3 символи";
                }
                if (!(isset($user['last_name']) && strlen($user['last_name'])>3)) {
                    $auth_error['last_name_length'] = "Прізвище повинно містити щонайменше 3 символи";
                }
                if (!(isset($user['email']) && preg_match("/.+@.+\..+/i", $user['email']))) {
                    $auth_error['email'] = "Введено некоректний email";
                }

                if (count($auth_error) > 0) {
                    echo json_encode($auth_error);die;
                }
            }

            function auth_with_email ($user) {
                $user_exist = Users::emailExist($user);
                if ($user_exist == 0) {
                    $result = Users::addUser($user);
                    if (!$result) {
                        $auth_error['no_auth'] = "Реєстрація неможлива!";
                        echo json_encode($auth_error);die;
                    }
                } else {
                    $auth_error['user_exist'] = "Користувач з таким email вже існує";
                    echo json_encode($auth_error);
                    die;
                }
            }

            function auth_with_social ($user) {
                $socialIdentityExist = Users::socialIdentityExist($user);

                if ($socialIdentityExist == 0) {
                    $emailExist = Users::emailExist($user);

                    if ($emailExist == 0) {
                        $result = Users::addUser($user);
                        if (!$result) {
                            $auth_error['no_auth'] = "Реєстрація неможлива!";
                            echo json_encode($auth_error);die;
                        }
                    } else {
                        $auth_error['email_exist'] = "Користувач з таким email вже існує";
                        echo json_encode($auth_error);die;
                    }
                } else {
                    die;
                }
            }

            if (isset($user['token'])) {
                checkDataUser($user);
                auth_with_social($user);
            } elseif (isset($user['password']) && strlen($user['password'])>3) {
                checkDataUser($user);
                $user['is_verification'] = 0;
                auth_with_email($user);
            } else {
                checkDataUser($user);
                $auth_error['password'] = "Пароль повинен містити щонайменше 3 символи";
            }
        }
    }

    public function actionLogin()
    {
        require_once ROOT.'/models/Users.php';

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            $result = Users::userLogin($user);

            echo json_encode($result);
        }
    }

    public function actionIndex()
    {
        return true;
    }

    /**
     *  У разі успішного додавання товару(автомобіля) буде виведено json обєкт з унікальним
     * значенням щойно доданого товару (id).
     *
     * У випадку, якщо при записі в базу відбулась помилка, буде виведено json обєкт,
     * який повідомляє що сталась помилка.
     */
    public function actionAddNewCar () {

        require_once ROOT.'/models/Users.php';
        require_once ROOT.'/models/Products.php';

        $validate_errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $car = json_decode($_POST['car'], true);

            if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $car['brand'])) {
                $validate_errors['brand'] = "Введіть корректну марку автомобіля!";
            }
            if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $car['model'])) {
                $validate_errors['model'] = "Введіть корректну модель автомобіля!";
            }
            if (! preg_match('~[a-zA-z0-9_ -]{4,20}~i', $car['year'])) {
                $validate_errors['year'] = "Введіть корректний рік виготовлення автомобіля!";
            }
            if (! preg_match('~[a-zA-z0-9_-]{3,64}~i', $car['color'])) {
                $validate_errors['color'] = "Введіть корректний колір автомобіля!";
            }
            if (! preg_match('~[a-zA-z0-9_-]+~', $car['price'])) {
                $validate_errors['price'] = "Введіть корректну ціну автомобіля!";
            }
            if (Users::checkUserIdExist($car['author_id'])) {
                $validate_errors['author_id'] = "Користувач який намагається додати автомобіль не зареєстрований!";
            }

            if ($car['created'] < 1491159675) {
                $validate_errors['created'] = "Некоректно заданий час додавання нового автомобіля!";
            }

            if (count($validate_errors) > 0) {
                $result = json_encode($validate_errors);
            } else {
                $result = json_encode(Products::addNewCar($car));
            }

            echo $result;

        }
    }

    public function actionEditCar($id)
    {
//        echo "<br> SERVER";
//        var_dump($_SERVER);
//        echo "<br> GET";
//        var_dump($_GET);
//        echo "<br> POST";
//        var_dump($_POST);
//        echo "<br> REQUEST";
//        var_dump($_REQUEST);
//        echo "<br> ENV";
//        var_dump($_ENV);

/*        echo "<br> http_response_header";
        var_dump($http_response_header);*/


        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $validate_errors = [];

            $putdata = file_get_contents('php://input');
            parse_str($putdata, $edit_fields);
            $edit_fields = json_decode($edit_fields['edit_fields'], true);

            if (count($edit_fields) > 0) {
                if (isset($edit_fields['brand'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['brand'])) {
                        $validate_errors['brand'] = "Введіть корректну марку автомобіля!";
                    }
                }
                if (isset($edit_fields['model'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['model'])) {
                        $validate_errors['model'] = "Введіть корректну марку автомобіля!";
                    }
                }
                if (isset($edit_fields['year'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,20}~i', $edit_fields['year'])) {
                        $validate_errors['year'] = "Введіть корректну марку автомобіля!";
                    }
                }
                if (isset($edit_fields['color'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['color'])) {
                        $validate_errors['color'] = "Введіть корректну марку автомобіля!";
                    }
                }
                if (isset($edit_fields['price'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]+~i', $edit_fields['price'])) {
                        $validate_errors['price'] = "Введіть корректну марку автомобіля!";
                    }
                }
            } else {
                $validate_errors['change_not_found'] = 'Не внесено ніяких змін';
            }

            if (count($validate_errors) == 0) {
                if (Products::checkProductExist($id[0])) {
                    $result = json_encode(Products::updateProduct($id[0], $edit_fields));
                } else {
                    $product_error['is_not_exist'] = 'Товару з таким id не існує!';
                    $result = json_encode($product_error);
                }
            } else {
                $result = json_encode($validate_errors);
            }

            echo $result;
        }
    }
}