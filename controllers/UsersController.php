<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 18:40
 */
class UsersController extends AppController
{
    public function actionAdd()
    {
        if (isset($_SESSION['user']['id'])) header("Location: /products/my_products");

        $title = "Реєстрація";
        $auth_error = [];
        $error_api = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = [];
//            Реєстрація через соц мережу
            if (isset($_POST['token'])) {
                $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
                $user = json_decode($s, true);
                //$user['network'] - соц. сеть, через которую авторизовался пользователь
                //$user['identity'] - уникальная строка определяющая конкретного пользователя соц. сети
                //$user['first_name'] - имя пользователя
                //$user['last_name'] - фамилия пользователя\
               $user['token'] = $_POST['token'];
//                Реєстрація через email
            } elseif (isset($_POST['submit'])) {

                if (!(isset($_POST['first_name']) && strlen($_POST['first_name'])>3)) {
                    $auth_error['first_name_length'] = false;
                }

                if (!(isset($_POST['last_name']) && strlen($_POST['last_name'])>3)) {
                    $auth_error['last_name_length'] = false;
                }

                if (!(isset($_POST['email']) && preg_match("/.+@.+\..+/i", $_POST['email']))) {
                    $auth_error['email'] = false;
                }

                if (!(isset($_POST['password']) && strlen($_POST['password'])>3)) {
                    $auth_error['password_length'] = false;
                }

                if (!(isset($_POST['repeat_password']) && ($_POST['password'] == $_POST['repeat_password']))) {
                    $auth_error['repeat_password'] = false;
                }

                if(count($auth_error) == 0) {
                    $user['first_name'] = $_POST['first_name'];
                    $user['last_name'] = $_POST['last_name'];
                    $user['email'] = $_POST['email'];
                    $user['password'] = $_POST['password'];
                }
            }

            $user_info = json_encode($user);

            if(count($auth_error) == 0) {
                if( $curl = curl_init() ) {
                    curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/add_new_user/');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$user_info");
                    $result = curl_exec($curl);
                    curl_close($curl);

                    $error_api = json_decode($result, true);
//                    var_dump($error_api);
//                    die;
                }
                if (count($error_api) == 0) {
                    $this->actionLogin();
                }
            }
        }

        require_once ROOT.'/views/users/add.php';
        return true;
    }

    public function actionLogin()
    {
        if (isset($_SESSION['user']['id'])) {
            header("Location: /products/my_products");
        }

        $title = "Вхід";

        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = [];

            if(isset($_POST['token'])) {
                $s = file_get_contents('http://ulogin.ru/token.php?token=' . $_POST['token'] . '&host=' . $_SERVER['HTTP_HOST']);
                $user = json_decode($s, true);
                $user['token'] = $_POST['token'];

            } elseif (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {

                $user['email'] = $_POST['email'];
                $user['password'] = $_POST['password'];
            }

            $user_info = json_encode($user);
            if( $curl = curl_init() ) {
                curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/login/');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$user_info");
                $result = curl_exec($curl);
                curl_close($curl);

                $user_data = json_decode($result, true);

                if($user_data) {
                    $this->loginUser($user_data);
                    header("Location: /products/my_products");
                } else {
                    $validate_errors['user_does_not_exist'] = false;
                }
            }
        }

        require_once ROOT.'/views/users/login.php';
        return true;
    }

    public function actionLogout ()
    {
        unset($_SESSION['user']);
        header("Location: /users/login/");
    }
}