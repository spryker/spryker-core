<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Router\Plugin\EventDispatcher;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\Router\Plugin\Application\RouterApplicationPlugin;
use Spryker\Glue\Router\Router\ChainRouter;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\EventListener\RouterListener;

/**
 * @method \Spryker\Glue\Router\RouterConfig getConfig()
 * @method \Spryker\Glue\Router\RouterFactory getFactory()
 */
class RouterListenerEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const BC_FEATURE_FLAG_ROUTER_LISTENER = 'BC_FEATURE_FLAG_ROUTER_LISTENER';

    /**
     * {@inheritDoc}
     * - Adds a RouteListener to the EventDispatcher.
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
        $container = $this->disableSilexRouter($container);

        $eventDispatcher = $this->addSubscriber($eventDispatcher, $container);

        return $eventDispatcher;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function disableSilexRouter(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::BC_FEATURE_FLAG_ROUTER_LISTENER, false);

        return $container;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addSubscriber(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addSubscriber(new RouterListener(
            $this->getChainRouter($container),
            $this->getRequestStack($container)
        ));

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Glue\Router\Router\ChainRouter
     */
    protected function getChainRouter(ContainerInterface $container): ChainRouter
    {
        return $container->get(RouterApplicationPlugin::SERVICE_ROUTER);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(ContainerInterface $container): RequestStack
    {
        return $container->get(RouterApplicationPlugin::SERVICE_REQUEST_STACK);
    }
}
