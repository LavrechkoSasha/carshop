<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:31
 */
class ApiController
{
    public function actionAllProducts()
    {
        require_once ROOT.'/models/Products.php';

        $result = Products::getAllProducts();

        echo $result;

        return true;
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
                $user_exist = Users::userExist($user);
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
                    //Визвати метод входу на сайт
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

            $result = Users::checkLoginExists($user);

            echo json_encode($result);
        }
    }

    public function actionIndex()
    {
        return true;
    }
}