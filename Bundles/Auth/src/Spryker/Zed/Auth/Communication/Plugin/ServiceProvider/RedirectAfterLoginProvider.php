<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\Url\Url;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacade getFacade()
 */
class RedirectAfterLoginProvider extends AbstractPlugin implements ServiceProviderInterface
{

    const REFERER = 'referer';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
        $app['dispatcher']->addListener(KernelEvents::RESPONSE, [$this, 'onKernelResponse']);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $requestUri = $event->getRequest()->getRequestUri();

        if (preg_match('/_profiler/', $requestUri)) {
            return;
        }

        $this->handleRedirectToLogin($event);
        $this->handleRedirectFromLogin($event);
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    protected function handleRedirectToLogin(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        if (!($response instanceof RedirectResponse)) {
            return;
        }

        $targetUrl = $response->getTargetUrl();
        if ($targetUrl !== AuthConfig::DEFAULT_URL_LOGIN) {
            return;
        }

        $redirectTo = $event->getRequest()->getRequestUri();

        if ($redirectTo === AuthConfig::DEFAULT_URL_REDIRECT) {
            return;
        }

        $url = Url::generate($targetUrl, [static::REFERER => $redirectTo]);
        $event->setResponse(new RedirectResponse($url->build()));
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return void
     */
    protected function handleRedirectFromLogin(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getPathInfo() !== AuthConfig::DEFAULT_URL_LOGIN) {
            return;
        }

        $referer = $request->query->get(static::REFERER);
        if (!$referer || !$this->isAuthenticated($request)) {
            return;
        }

        $event->setResponse(new RedirectResponse($referer));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isAuthenticated(Request $request)
    {
        $authFacade = $this->getFacade();
        $token = null;

        if ($authFacade->hasCurrentUser()) {
            $token = $authFacade->getCurrentUserToken();
        }

        if ($request->headers->get(AuthConstants::AUTH_TOKEN)) {
            $token = $request->headers->get(AuthConstants::AUTH_TOKEN);
        }

        if (!$authFacade->isAuthenticated($token)) {
            return false;
        }

        return true;
    }

}
