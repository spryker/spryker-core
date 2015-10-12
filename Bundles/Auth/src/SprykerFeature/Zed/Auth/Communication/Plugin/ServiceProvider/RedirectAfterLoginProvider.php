<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Auth\Communication\Plugin\ServiceProvider;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Silex\Application;
use Silex\ServiceProviderInterface;
use SprykerFeature\Zed\Auth\Business\AuthFacade;
use SprykerFeature\Zed\Auth\Communication\AuthDependencyContainer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method AuthDependencyContainer getDependencyContainer()
 * @method AuthFacade getFacade()
 */
class RedirectAfterLoginProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const REQUEST_URI = 'request uri';
    const LOGIN_URI = '/auth/login';

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::REQUEST, [$this, 'onKernelRequest']);
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * @param GetResponseEvent $event
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
        if ($request->getMethod() !== 'GET') {
            return false;
        }

        if ($this->isAuthorized($request)) {
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
    protected function isAuthorized(Request $request)
    {
        $facadeAuth = $this->getFacade();
        $token = null;

        if ($facadeAuth->hasCurrentUser()) {
            $token = $facadeAuth->getCurrentUserToken();
        }

        if ($request->headers->get('Auth-Token')) {
            $token = $request->headers->get('Auth-Token');
        }

        if (!$facadeAuth->isAuthorized($token)) {
            return false;
        }

        return true;
    }

    /**
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        if ($session->has(self::REQUEST_URI) && $this->isAuthorized($request)) {
            $event->setResponse(new RedirectResponse($session->get(self::REQUEST_URI)));
            $session->remove(self::REQUEST_URI);
        }
    }

}
