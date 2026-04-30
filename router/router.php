<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\Twig;


function use_router($app) {

    $app->get('/', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'home.page.twig');
    });

    $app->get('/about-me', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'about_me.page.twig');
    });

    $app->get('/niche-markets', function (Request $request, Response $response) {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'niche_markets.page.twig');
    });

}

?>