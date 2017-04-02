<?php

/**
 * Created by PhpStorm.
 * User: Sasha
 * Date: 31.03.2017
 * Time: 22:04
 */
class Router
{
    private $routes;

    public function __construct()
    {
        $this->routes = require(ROOT."/config/routes.php");
//        var_dump($this->routes);
    }

    private function getURI()
    {
        if(!empty($_SERVER['REQUEST_URI'])) {

            //          для дебага
            $pattern = '~(.+)\?XDEBUG.+~i';
            $replacement = '${1}';
            $uri = preg_replace($pattern, $replacement, $_SERVER['REQUEST_URI']);

            return trim($uri, '/');
        }
    }

    public function run()
    {
        $uri = $this->getURI();

        foreach ($this->routes as $uri_pattern => $path) {

            if (preg_match("~$uri_pattern~", $uri)) {

                $internalRoute = preg_replace("~$uri_pattern~", $path, $uri);

                $segment = explode('/', $internalRoute);

                $controllerName = array_shift($segment)."Controller";
                $controllerName = ucfirst($controllerName);
                $actionName = 'action'.ucfirst(array_shift($segment));
                $controllerFile = ROOT.'/controllers/'.$controllerName.'.php';

                $parameters = $segment;

                if (file_exists($controllerFile)) {

                    require_once $controllerFile;

                    $controllerObject = new $controllerName;
                    $result = $controllerObject->$actionName($parameters);
                    if($result != null) {
                        break;
                    }
                }
            }
        }
    }
}