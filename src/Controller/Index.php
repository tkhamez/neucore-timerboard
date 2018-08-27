<?php
namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\View;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Index extends BaseController
{
    public function board(Request $request, Response $response): ResponseInterface
    {
        $limit = 20;
        $page = (int) $request->getParam('page', 1);
        $from = ($page - 1) * $limit;

        $activeEvents = $this->eventRepository->findActiveTimers();
        $expiredEvents = $this->eventRepository->findExpiredTimers($from, $limit);

        $num = $this->eventRepository->numberOfExpiredTimers();
        $pages = floor($num / $limit);

        $view = new View(ROOT_DIR . '/views/index.php');
        $view->addVar('isAdmin', $this->security->isAdmin());
        $view->addVar('authName', $this->security->getAuthorizedName());
        $view->addVar('activeEvents', $activeEvents);
        $view->addVar('expiredEvents', $expiredEvents);
        $view->addVar('pages', $pages);

        $response->write($view->getContent());

        return $response;
    }
}
