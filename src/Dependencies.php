<?php declare(strict_types = 1);

$injector = new \Auryn\Injector;

$injector->alias('Http\HttpRequest','Symfony\Component\HttpFoundation\Request');
$injector->share('Http\HttpRequest');
$injector->define('Http\HttpRequest', [
    ':get' => $_GET,
    ':post' => $_POST,
    ':cookies' => $_COOKIE,
    ':files' => $_FILES,
    ':server' => $_SERVER,
]);

$injector->alias('Http\HttpResponse','Symfony\Component\HttpFoundation\Response');
$injector->share('Http\HttpResponse');

$injector->alias('NTeste\Template\Renderer', 'NTeste\Template\TwigRenderer');

$injector->define('NTeste\Page\FilePageReader', [
    ':pageFolder' => __DIR__ . '/../pages',
]);
$injector->alias('NTeste\Page\PageReader', 'NTeste\Page\FilePageReader');
$injector->share('NTeste\Page\FilePageReader');


$injector->delegate('Twig\Environment', function () use ($injector) {
    $loader = new Twig\Loader\FilesystemLoader(dirname(__DIR__) . '/templates');
    $twig = new Twig\Environment($loader);
    return $twig;
});


return $injector;