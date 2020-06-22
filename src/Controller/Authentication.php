<?php
namespace Brave\TimerBoard\Controller;

use Brave\Sso\Basics\AuthenticationProvider;
use Brave\TimerBoard\Provider\RoleProviderInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Response;
use SlimSession\Helper;

class Authentication
{
    /**
     * @var array
     */
    private $settings;

    /**
     * @var Helper
     */
    private $session;

    /**
     * @var RoleProviderInterface
     */
    private $roleProvider;

    /**
     * @var AuthenticationProvider
     */
    private $authenticationProvider;

    /**
     * @throws \Exception
     * @noinspection PhpUnusedParameterInspection
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response)
    {
        $state = $this->authenticationProvider->generateState();
        $this->session->set('ssoState', $state);

        $response->getBody()->write(str_replace([
            '{{loginUrl}}',
            '{{appName}}',
            '{{footer}}',
            '{{logo}}',
            '{{loginHint}}'
        ], [
            $this->authenticationProvider->buildLoginUrl($state),
            htmlspecialchars($this->settings['app.name']),
            htmlspecialchars($this->settings['app.footer']),
            htmlspecialchars($this->settings['app.login_logo']),
            $this->settings['app.login_hint'], // contains HTML
        ], file_get_contents(ROOT_DIR . '/views/sso_page.html')));

        return $response;
    }

    public function __construct(ContainerInterface $container)
    {
        $this->settings = $container->get('settings');
        $this->session = $container->get(Helper::class);
        $this->roleProvider = $container->get(RoleProviderInterface::class);
        $this->authenticationProvider = $container->get(AuthenticationProvider::class);
    }

    public function callback(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        $queryParameters = $request->getQueryParams();
        $code = $queryParameters['code'] ?? null;
        $state = $queryParameters['state'] ?? null;
        $sessionState = $this->session->get('ssoState');

        $eveAuthentication = null;
        try {
            if (! $code || ! $state) {
                throw new \Exception('Invalid SSO state, please try again.');
            }
            $eveAuthentication = $this->authenticationProvider->validateAuthenticationV2($state, $sessionState, $code);
        } catch(\Exception $e) {
            error_log((string)$e);
        }

        $this->session->set('eveAuth', $eveAuthentication);
        $this->roleProvider->clear();

        return $response->withRedirect('/');
    }

    /**
     * @noinspection PhpUnusedParameterInspection
     * @noinspection PhpUnused
     */
    public function logout(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        $this->session->clear();

        return $response->withRedirect('/login');
    }
}
