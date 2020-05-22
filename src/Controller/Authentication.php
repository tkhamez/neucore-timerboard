<?php
namespace Brave\TimerBoard\Controller;

use Brave\Sso\Basics\AuthenticationController;
use Brave\TimerBoard\Provider\RoleProviderInterface;
use Brave\TimerBoard\SessionHandler;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;

class Authentication extends AuthenticationController
{
    /**
     * @var SessionHandler
     */
    private $sessionHandler;

    /**
     * @var RoleProviderInterface
     */
    private $roleProvider;

    /**
     * @var string
     */
    protected $template = ROOT_DIR . '/views/sso_page.html';

    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $response = parent::index($request, $response);

        $settings = $this->container->get('settings');
        $body = (new Response())->getBody();
        $body->write(str_replace(
            [
                '{{appName}}',
                '{{footer}}',
                '{{logo}}',
                '{{loginHint}}'
            ],
            [
                htmlspecialchars($settings['app.name']),
                htmlspecialchars($settings['app.footer']),
                htmlspecialchars($settings['app.login_logo']),
                $settings['app.login_hint'], // contains HTML
            ],
            $response->getBody()->__toString()
        ));

        return $response->withBody($body);
    }

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);

        $this->sessionHandler = $this->container->get(SessionHandler::class);
        $this->roleProvider = $this->container->get(RoleProviderInterface::class);
    }

    public function callback(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        try {
            parent::auth($request, $response, true);
        } catch(\Exception $e) {
            error_log((string)$e);
        }

        $this->roleProvider->clear();

        return $response->withRedirect('/');
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnused
     */
    public function logout(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        $this->sessionHandler->clear();

        return $response->withRedirect('/login');
    }
}
