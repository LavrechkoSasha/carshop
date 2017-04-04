<?php
require_once ROOT.'/models/Users.php';
require_once ROOT.'/models/Products.php';
require_once ROOT.'/models/Admin.php';

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
        return true;
    }

    public function actionViewProduct($id)
    {
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
        return true;
    }

    public function actionAddNewUser()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            function checkDataUser ($user) {

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
                /**
                 *  Вкзазуємо значення поля "is_verification" за замовчування 1
                 *  що буде вказувати на те, что цей користувач підтверджений
                 *  оскільки ввійшов через соц. мережу
                 */
                $user['is_verification'] = '1';

                auth_with_social($user);
            } elseif (isset($user['password']) && strlen($user['password'])>3) {
                checkDataUser($user);

                /**
                 *  Вкзазуємо значення поля "is_verification" за замовчування 0
                 *  що буде вказувати на те, что цей користувач ще не підтверджений адміном
                 */
                $user['is_verification'] = '0';

                auth_with_email($user);
            } else {
                checkDataUser($user);
                $auth_error['password'] = "Пароль повинен містити щонайменше 3 символи";
            }
        }
        return true;
    }

    /**
     * При успішній авторизації видає дані про користуваа в форматі json
     * в іншому випадку null
     *
     * @return bool
     */
    public function actionLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            $result = Users::userLogin($user);

            echo json_encode($result);
        }
        return true;
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

        return true;
    }

    public function actionEditCar($id)
    {
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
                        $validate_errors['model'] = "Введіть корректну модель автомобіля!";
                    }
                }
                if (isset($edit_fields['year'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,20}~i', $edit_fields['year'])) {
                        $validate_errors['year'] = "Введіть корректний рік автомобіля!";
                    }
                }
                if (isset($edit_fields['color'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['color'])) {
                        $validate_errors['color'] = "Введіть корректний колір автомобіля!";
                    }
                }
                if (isset($edit_fields['price'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]+~i', $edit_fields['price'])) {
                        $validate_errors['price'] = "Введіть корректну ціну автомобіля!";
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
        return true;
    }

    /* ADMIN */

    public function actionAddAdminUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            function checkDataUser ($user) {
                $auth_error = [];

                if (!(isset($user['first_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $user['first_name']))) {
                    $auth_error['first_name_length'] = "Імя повинно містити щонайменше 3 символи";
                }
                if (!(isset($user['last_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $user['last_name']))) {
                    $auth_error['last_name_length'] = "Прізвище повинно містити щонайменше 3 символи";
                }
                if (!(isset($user['email']) && preg_match("/.+@.+\..+/i", $user['email']))) {
                    $auth_error['email'] = "Введено некоректний email";
                }

                if (count($auth_error) > 0) {
                    echo json_encode($auth_error);die;
                }
            }

            function addAdminUser ($user) {
                $user_exist = Admin::adminEmailExist($user);
                if ($user_exist == 0) {
                    $result = Admin::addAdminUser($user);
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

            if (isset($user['password']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $user['password'])) {
                checkDataUser($user);

                /**
                 *  Вкзазуємо значення поля "is_admin" за замовчування 1
                 *  що буде вказувати на те, что цей користувач є адміном
                 */
                $user['is_admin'] = 1;

                addAdminUser($user);
            } else {
                checkDataUser($user);
                $auth_error['password'] = "Пароль повинен містити щонайменше 3 символи";
            }
        }
        return true;
    }

    public function actionLoginAdminUser()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = json_decode($_POST['user'], true);

            $result = Admin::adminLogin($user);

            echo json_encode($result);
        }
        return true;
    }

    public function actionUsersList($parameters = null)
    {
        if ($parameters != null && preg_match('~[01]~', $parameters[0])) {
            $filter['is_verification'] = $parameters[0];
        } else {
            $filter = [];
        }

        $result =  json_encode(Admin::getUsersList($filter));
        echo $result;
        return true;
    }

    public function actionViewUser($id)
    {
        if (preg_match('~^[a-f0-9]{24}$~', $id[0])) {
            if (Admin::checkUserExist($id[0])) {
                $result = Admin::getUser($id[0]);
                echo json_encode($result);
            } else {
                $product_error['is_not_exist'] = 'Користувача з таким id не існує!';
                echo json_encode($product_error);
            }
        } else {
            $product_error['is_not_exist'] = 'Користувача з таким id не існує!';
            echo json_encode($product_error);
        }
        return true;
    }

    public function actionEditUser($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $validate_errors = [];

            $putdata = file_get_contents('php://input');
            parse_str($putdata, $edit_fields);
            $edit_fields = json_decode($edit_fields['edit_fields'], true);

            if (count($edit_fields) > 0) {
                if (isset($edit_fields['first_name'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['first_name'])) {
                        $validate_errors['first_name'] = "Введіть коректне і'мя";
                    }
                }
                if (isset($edit_fields['last_name'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['last_name'])) {
                        $validate_errors['last_name'] = "Введіть коректне прізвище";
                    }
                }
                if (isset($edit_fields['email'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,20}~i', $edit_fields['email'])) {
                        $validate_errors['email'] = "Введіть коректний email";
                    }
                }
                if (isset($edit_fields['password'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]{3,64}~i', $edit_fields['password_length'])) {
                        $validate_errors['password_length'] = "Введіть коректний пароль";
                    }
                }
                if (isset($edit_fields['is_verification'])) {
                    if (! preg_match('~[a-zA-z0-9_ -]+~i', $edit_fields['is_verification'])) {
                        $validate_errors['is_verification'] = "Не вірне підтвердження!";
                    }
                }
            } else {
                $validate_errors['change_not_found'] = 'Не внесено ніяких змін';
            }

            if (count($validate_errors) == 0) {
                if (Admin::checkUserExist($id[0])) {
                    $result = json_encode(Admin::updateUser($id[0], $edit_fields));
                } else {
                    $product_error['is_not_exist'] = 'Користувача з таким id не існує!';
                    $result = json_encode($product_error);
                }
            } else {
                $result = json_encode($validate_errors);
            }
            echo $result;
        }
        return true;
    }

}