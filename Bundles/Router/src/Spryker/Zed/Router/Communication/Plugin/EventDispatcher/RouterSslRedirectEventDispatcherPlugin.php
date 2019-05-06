<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Use this plugin when you need to redirect from http to https or vice versa.
 *
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 * @method \Spryker\Zed\Router\Communication\RouterCommunicationFactory getFactory()
 * @method \Spryker\Zed\Router\Business\RouterFacadeInterface getFacade()
 */
class RouterSslRedirectEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds a Listener to the EventDispatcher.
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
        $eventDispatcher = $this->addListener($eventDispatcher);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addListener(EventDispatcherInterface $eventDispatcher): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::REQUEST, function (GetResponseEvent $event) {
            $request = $event->getRequest();

            if ($this->shouldBeSsl($request)) {
                $fakeRequest = clone $request;
                $fakeRequest->server->set('HTTPS', true);

                $event->setResponse(new RedirectResponse($fakeRequest->getUri(), 301));
            }
        });

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function shouldBeSsl(Request $request)
    {
        $requestIsSecure = $request->isSecure();

        if (!$requestIsSecure && !$this->getConfig()->isSslEnabled()) {
            return false;
        }

        $isSslExcludedResource = $this->isSslExcludedRouteName($request);

        return (!$requestIsSecure && !$isSslExcludedResource);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isSslExcludedRouteName(Request $request)
    {
        return in_array($request->getPathInfo(), $this->getConfig()->getSslExcludedRouteNames());
    }
}
