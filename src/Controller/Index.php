<?php

namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Index extends BaseController
{
    public function board(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $events = $this->eventRepository->findAll();

        $view = new View(ROOT_DIR . '/views/index.php');
        $view->addVar('events', $events);
        $view->addVar('isAdmin', $this->security->isAdmin());

        $response->write($view->getContent());

        return $response;
    }
}
