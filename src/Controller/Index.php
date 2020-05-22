<?php
namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\View;
use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Index extends BaseController
{
    /** @noinspection PhpUnused */
    public function board(Request $request, Response $response): ResponseInterface
    {
        $limit = 30;
        $page = (int) $request->getParam('page', 1);
        $page = $page < 1 ? 1 : $page;
        $from = ($page - 1) * $limit;

        $activeEvents = $this->eventRepository->findActiveTimers();
        $expiredEvents = $this->eventRepository->findExpiredTimers($from, $limit);

        $num = $this->eventRepository->numberOfExpiredTimers();
        $pages = ceil($num / $limit);

        $view = new View(ROOT_DIR . '/views/index.php');
        $view->addVar('head', $this->head);
        $view->addVar('foot', $this->foot);
        $view->addVar('isAdmin', $this->security->isAdmin());
        $view->addVar('activeEvents', $activeEvents);
        $view->addVar('expiredEvents', $expiredEvents);
        $view->addVar('currentPage', $page);
        $view->addVar('pages', $pages);

        $response->write($view->getContent());

        return $response;
    }
}
