<?php
require_once ROOT.'/models/Products.php';

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 01.04.2017
 * Time: 15:23
 */
class ProductsController
{
    public function actionIndex($parameters)
    {
        if( $curl = curl_init() ) {
            curl_setopt($curl, CURLOPT_URL, $_SERVER['SERVER_NAME'].'/api/all_products/');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
            $result = curl_exec($curl);
            curl_close($curl);

            $allProducts = json_decode($result, true);
        }
        require_once ROOT.'/views/products/index.php';
        return true;
    }

    public function actionView($parameters)
    {
//        var_dump($parameters);
//        echo 'product view';
        return true;
    }


}