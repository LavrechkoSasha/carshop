<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:31
 */
class IndexController extends AppController
{
    public function actionIndex($parameters)
    {
        $title = "Головна";
//        var_dump($_SESSION);
//        $b = $this->a." which rename b";
//        echo 'index/index';
//        echo "<br> $b";
//
//        echo "<a href='http://carshop/users/add/'>http://carshop/users/add/</a>";
        var_dump($_SESSION);
        require_once ROOT.'/views/index/index.php';
        return true;
    }


    public function actionView($parameters)
    {
        var_dump($parameters);
        echo "view";
        return true;
    }
}