<?php
namespace Brave\TimerBoard\Controller;

use Brave\TimerBoard\Entity\Event;
use Brave\TimerBoard\View;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Admin extends BaseController
{
    public function index(ServerRequestInterface $request, Response $response, array $args): ResponseInterface
    {
        $view = new View(ROOT_DIR . '/views/admin.php');
        $view->addVar('isAdmin', $this->security->isAdmin());
        $view->addVar('authName', $this->security->getAuthorizedName());
        $view->addVar('event', $this->getEvent($args));

        $response->write($view->getContent());

        return $response;
    }

    public function save(Request $request, Response $response, array $args): ResponseInterface
    {
        $event = $this->getEvent($args);

        $event->location = (string) $request->getParam('location');
        $event->priority = (string) $request->getParam('priority');
        $event->type = (string) $request->getParam('type');
        $event->structure = (string) $request->getParam('structure');
        try {
            $dateTime = new \DateTime(
                (string) $request->getParam('date') . ' ' . (string) $request->getParam('time'));
        } catch (\Exception $e) {
            $dateTime = new \DateTime('@0');
        }
        $event->eventTime = $dateTime;
        $event->result = $request->getParam('result');

        $this->entityManager->persist($event);
        $this->entityManager->flush();

        return $response->withRedirect('/');
    }

    private function getEvent(array $args): Event
    {
        $editId = isset($args['id']) && (int) $args['id'] > 0 ? (int) $args['id'] : 0;
        if ($editId > 0) {
            $event = $this->eventRepository->find($editId);
        }
        if (! isset($event)) {
            $event = new Event();
        }

        return $event;
    }
}
