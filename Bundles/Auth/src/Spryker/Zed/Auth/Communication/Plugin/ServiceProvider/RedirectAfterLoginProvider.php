<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Auth\Communication\Plugin\ServiceProvider;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Auth\Business\AuthFacade;
use Spryker\Zed\Auth\Communication\AuthCommunicationFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method AuthCommunicationFactory getCommunicationFactory()
 * @method AuthFacade getFacade()
 */
class RedirectAfterLoginProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const REQUEST_URI = 'request uri';
    const LOGIN_URI = '/auth/login';

    /**
     * @param Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::REQUEST, [$this, 'onKernelRequest']);
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * @param GetResponseEvent $event
     *
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->canRedirectAfterLogin($request)) {
            $requestUri = $request->getRequestUri();
            $request->getSession()->set(self::REQUEST_URI, $requestUri);
        }
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function canRedirectAfterLogin(Request $request)
    {
        if ($request->getMethod() !== Request::METHOD_GET) {
            return false;
        }

        if ($this->isAuthenticated($request)) {
            return false;
        }

        $requestUri = $request->getRequestUri();

        if ($requestUri === self::LOGIN_URI) {
            return false;
        }

        if (preg_match('/_profiler/', $requestUri)) {
            return false;
        }

        return true;
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function isAuthenticated(Request $request)
    {
        $facadeAuth = $this->getFacade();
        $token = null;

        if ($facadeAuth->hasCurrentUser()) {
            $token = $facadeAuth->getCurrentUserToken();
        }

        if ($request->headers->get(AuthConfig::AUTH_TOKEN)) {
            $token = $request->headers->get(AuthConfig::AUTH_TOKEN);
        }

        if (!$facadeAuth->isAuthenticated($token)) {
            return false;
        }

        return true;
    }

    /**
     * @param FilterResponseEvent $event
     *
     * @return null
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        if (!$request->hasSession()) {
            return null;
        }
        $session = $request->getSession();
        if ($session->has(self::REQUEST_URI) && $this->isAuthenticated($request)) {
            $event->setResponse(new RedirectResponse($session->get(self::REQUEST_URI)));
            $session->remove(self::REQUEST_URI);
        }
    }

}
