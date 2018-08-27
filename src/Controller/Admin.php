<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Admin extends BaseController
{
    public function index(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $view = new View(ROOT_DIR . '/views/admin.php');
        $view->addVar('isAdmin', $this->security->isAdmin());

        $response->write($view->getContent());

        return $response;
    }
}
