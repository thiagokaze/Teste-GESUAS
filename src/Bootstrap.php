<?php declare(strict_types = 1);

namespace NTeste;

require __DIR__ . '/../vendor/autoload.php';

error_reporting(E_ALL);

$environment = 'development';
$injector = include('Dependencies.php');

/**
* Register the error handler
*/
$whoops = new \Whoops\Run;
if ($environment !== 'production') {
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
} else {
    $whoops->pushHandler(function($e){
        echo 'Todo: Friendly error page and send an email to the developer';
    });
}
$whoops->register();

// $request = Request::createFromGlobals();
// $response = new Response();
// $response->headers->set('Content-Type', 'text/plain');
// $response->setContent('Hello World');
// $response->send();

// $dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
//     $r->addRoute('GET', '/hello-world', function () {
//         echo 'Hello World';
//     });
//     $r->addRoute('GET', '/another-route', function () {
//         echo 'This works too';
//     });
// });

$request = $injector->make('Http\HttpRequest');
$response = $injector->make('Http\HttpResponse');


$routeDefinitionCallback = function (\FastRoute\RouteCollector $r) {
    $routes = include('Routes.php');
    foreach ($routes as $route) {
        $r->addRoute($route[0], $route[1], $route[2]);
    }
};

$dispatcher = \FastRoute\simpleDispatcher($routeDefinitionCallback);

$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
        $response->setContent('404 - Page not found');
        $response->setStatusCode(404);
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response->setContent('405 - Method not allowed');
        $response->setStatusCode(405);
        break;
    case \FastRoute\Dispatcher::FOUND:
        $className = $routeInfo[1][0];
        $method = $routeInfo[1][1];
        $vars = $routeInfo[2];
        
        $class = $injector->make($className);
        $class->$method($vars);
        break;
}




