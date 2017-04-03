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
        $all_product = [];
        $title = "Головна";


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