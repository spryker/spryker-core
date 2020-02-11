<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Auth\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Auth\AuthConstants;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Auth\Communication\AuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\Auth\Business\AuthFacadeInterface getFacade()
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 * @method \Spryker\Zed\Auth\Persistence\AuthQueryContainerInterface getQueryContainer()
 */
class AuthorizationEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener for the `\Symfony\Component\HttpKernel\KernelEvents::REQUEST` event, which will redirect to the login page if user is not authorized.
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
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (GetResponseEvent $event) {
            return $this->onKernelRequest($event);
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
     *
     * @return \Symfony\Component\HttpKernel\Event\GetResponseEvent
     */
    protected function onKernelRequest(GetResponseEvent $event): GetResponseEvent
    {
        $config = $this->getConfig();
        $authFacade = $this->getFacade();

        $request = $event->getRequest();

        $module = $request->attributes->get('module');
        $controller = $request->attributes->get('controller');
        $action = $request->attributes->get('action');

        if ($authFacade->isIgnorable($module, $controller, $action)) {
            return $event;
        }

        $token = null;

        if ($authFacade->hasCurrentUser()) {
            $token = $authFacade->getCurrentUserToken();
        }

        if ($request->headers->get(AuthConstants::AUTH_TOKEN)) {
            $token = $request->headers->get(AuthConstants::AUTH_TOKEN);
        }

        if ($authFacade->isAuthenticated($token)) {
            return $event;
        }

        $event->setResponse(new RedirectResponse($config->getLoginPageUrl()));

        return $event;
    }
}
