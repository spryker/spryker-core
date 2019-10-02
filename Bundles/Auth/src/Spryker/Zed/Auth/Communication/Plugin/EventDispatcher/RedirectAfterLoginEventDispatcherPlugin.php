<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Auth\AuthConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 */
class RedirectAfterLoginEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const REFERER = 'referer';

    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::RESPONSE` event to redirect the user after login.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::RESPONSE, function (FilterResponseEvent $event) {
            return $this->onKernelResponse($event);
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\FilterResponseEvent
     */
    protected function onKernelResponse(FilterResponseEvent $event): FilterResponseEvent
    {
        $requestUri = $event->getRequest()->getRequestUri();

        if (preg_match('/_profiler/', $requestUri)) {
            return $event;
        }

        $event = $this->handleRedirectToLogin($event);
        $event = $this->handleRedirectFromLogin($event);

        return $event;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\FilterResponseEvent
     */
    protected function handleRedirectToLogin(FilterResponseEvent $event): FilterResponseEvent
    {
        $response = $event->getResponse();
        if (!($response instanceof RedirectResponse)) {
            return $event;
        }

        $targetUrl = $response->getTargetUrl();
        if ($targetUrl !== AuthConfig::DEFAULT_URL_LOGIN) {
            return $event;
        }

        $redirectTo = $this->getUrlToRedirectBackTo($event);
        if ($redirectTo === AuthConfig::DEFAULT_URL_REDIRECT) {
            return $event;
        }

        $query = [];
        if ($redirectTo) {
            $query[static::REFERER] = $redirectTo;
        }

        $url = Url::generate($targetUrl, $query);
        $event->setResponse(new RedirectResponse($url->build()));

        return $event;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return string|null
     */
    protected function getUrlToRedirectBackTo(FilterResponseEvent $event): ?string
    {
        $urlToRedirectBackTo = $event->getRequest()->getRequestUri();

        $isGetRequest = $event->getRequest()->isMethod('GET');
        if (!$isGetRequest) {
            return null;
        }

        return $urlToRedirectBackTo;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterResponseEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\FilterResponseEvent
     */
    protected function handleRedirectFromLogin(FilterResponseEvent $event): FilterResponseEvent
    {
        $request = $event->getRequest();

        if ($request->getPathInfo() !== AuthConfig::DEFAULT_URL_LOGIN) {
            return $event;
        }
        if (!$this->isAuthenticated($request)) {
            return $event;
        }

        $referer = $this->filterReferer($request->query->get(static::REFERER));
        if (!$referer) {
            return $event;
        }

        $event->setResponse(new RedirectResponse($referer));

        return $event;
    }

    /**
     * Asserts that no external URL can be injected into the redirect URL.
     *
     * @param string|null $url
     *
     * @return string|null
     */
    protected function filterReferer(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        if (substr($url, 0, 1) !== '/' || substr($url, 0, 2) === '//') {
            return null;
        }

        return $url;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isAuthenticated(Request $request): bool
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
