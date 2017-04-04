<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 04.04.2017
 * Time: 1:50
 */
class AdminController extends AppController
{

    public function actionIndex()
    {
        if (! isset($_SESSION['user']['is_admin'])) header("Location: /products/index");

        require_once ROOT.'/views/admin/index.php';
        return true;
    }

    public function actionEditUser($id)
    {
        if (! isset($_SESSION['user']['is_admin'])) header("Location: /products/index");

        $id = $id[0];
        $api_error = [];
        $validate_errors = [];
        $edit_fields = [];


        // Запит на отримання даних про користувача
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/view_user/$id");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $result_arr = json_decode($result, true);

            if (!isset($result_arr['_id'])) {
                $api_error = $result_arr;
                $title = "Помилка :(";
            } else {
                $user_info = $result_arr;
            }
        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }

        if (isset($user_info)) {

            $first_name = $user_info['first_name'];
            $last_name = $user_info['last_name'];
            $email = $user_info['email'];

            $is_verification = $user_info['is_verification'];

            if (isset($user_info['password'])) {
                $password = $user_info['password'];
            }

            if (isset($_SESSION['user']['is_admin'])) {
                if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {

                    if (!(isset($_POST['first_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['first_name']))) $validate_errors['first_name_length'] = "Введіть коректне і'мя";
                    if (!(isset($_POST['last_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['last_name']))) $validate_errors['last_name_length'] = "Введіть коректне прізвище";
                    if (!(isset($_POST['email']) && preg_match("/.+@.+\..+/i", $_POST['email']))) $validate_errors['email'] = "Введіть коректний email";
                    if (! preg_match('~[01]{1}~', $_POST['is_verification'])) $validate_errors['is_verification'] = "Не вірне підтвердження!";

                    if (isset($_POST['password'])) {
                        if (preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['password'])) {
                            $validate_errors['password_length'] = "Введіть коректний пароль";
                        }
                    }

                    if (count($validate_errors) == 0) {
                        foreach ($_POST as $field_key => $field_value) {
                            if ($field_key == 'submit') continue;

                            if ($user_info[$field_key] != $_POST[$field_key]){
                                $edit_fields [$field_key] = $_POST[$field_key];
                            }
                        }

                        if (count($edit_fields) > 0) {
                            $edit_fields = json_encode($edit_fields);
                            if( $curl = curl_init() ) {
                                curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/edit_user/$id");
                                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                                curl_setopt($curl, CURLOPT_POST, TRUE);
                                curl_setopt($curl, CURLOPT_POSTFIELDS, "edit_fields=$edit_fields" );
                                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                                $result = curl_exec($curl);
                                curl_close($curl);

                                $result_arr = json_decode($result, true);

//                                var_dump($result);die;

                                if (!isset($result_arr['_id'])) {
                                    $api_error = $result_arr;
                                } else {
                                    header("Location: /admin/users");
                                }
                            }
                        } else {
                            $validate_errors['change_not_found'] = 'Не внесено ніяких змін';
                        }
                    }
                }
            } else {
                header("Location: /products/");
            }
        }

        $title = "Edit";

        require_once ROOT.'/views/admin/edit_user.php';
        return true;
    }

    public function actionUsersList()
    {
        if (! isset($_SESSION['user']['is_admin'])) header("Location: /products");

        $all_users = [];
        $title = "Користувачі";
        $check_users = [];
        $no_check_users = [];

        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/users_list/0");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);

            $check_users = json_decode($result, true);

            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME']."/api/users_list/1");
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);

            $no_check_users = json_decode($result, true);

            curl_close($curl);
        } else {
            $api_error['curl'] = 'Не вдалося звязатись з API!';
        }

        require_once ROOT.'/views/admin/users.php';
        return true;
    }

    public function actionLogin()
    {
        if (isset($_SESSION['user']['id']))  header("Location: /products/");

        $title = "Вхід";

        if($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = [];

            if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['password'])) {

                $user['email'] = $_POST['email'];
                $user['password'] = $_POST['password'];
            }

            $user_info = json_encode($user);

            if( $curl = curl_init() ) {
                curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/login_admin_user');
                curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$user_info");
                $result = curl_exec($curl);
                curl_close($curl);

                $user_data = json_decode($result, true);

                if($user_data) {
                    $this->loginUser($user_data);
                    header("Location: /admin/users");
                } else {
                    $validate_errors['user_does_not_exist'] = false;
                }
            }
        }

        require_once ROOT.'/views/admin/login.php';
        return true;
    }

    public function actionAdd()
    {
//        if (! isset($_SESSION['user']['is_admin'])) header("Location: /products");

        $title = "Реєстрація";
        $auth_error = [];
        $error_api = [];
        $flag_ok = false;

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = [];

            if (isset($_POST['submit'])) {

                if (!(isset($_POST['first_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['first_name']))) $auth_error['first_name_length'] = false;
                if (!(isset($_POST['last_name']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['last_name']))) $auth_error['last_name_length'] = false;
                if (!(isset($_POST['email']) && preg_match("/.+@.+\..+/i", $_POST['email']))) $auth_error['email'] = false;
                if (!(isset($_POST['password']) && preg_match('~[a-zA-z0-9_ -]{3,64}~i', $_POST['password']))) $auth_error['password_length'] = false;
                if (!(isset($_POST['repeat_password']) && ($_POST['password'] == $_POST['repeat_password']))) $auth_error['repeat_password'] = false;


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
                    curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/add_admin_user/');
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, "user=$user_info");
                    $result = curl_exec($curl);
                    curl_close($curl);

                    $error_api = json_decode($result, true);
                }
                if (count($error_api) == 0) {
                    $flag_ok = true;
                }
            }
        }

        require_once ROOT.'/views/admin/add.php';
        return true;
    }
}