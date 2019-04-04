<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RequestContextAwareInterface;

class RouterLocaleEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    protected const EVENT_PRIORITY_KERNEL_REQUEST = 16;
    protected const EVENT_PRIORITY_KERNEL_FINISH_REQUEST = 0;

    protected const SERVICE_URL_MATCHER = 'url_matcher';
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * {@inheritdoc}
     * - Adds event listener that set the locale to the router context.
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
        $eventDispatcher = $this->addListeners($eventDispatcher, $container);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addListeners(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher = $this->addRequestKernelEventListener($eventDispatcher, $container);
        $eventDispatcher = $this->addFinishRequestKernelEventListener($eventDispatcher, $container);

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addRequestKernelEventListener(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (GetResponseEvent $event) use ($container) {
                $request = $event->getRequest();
                $this->setRouterContext($request, $this->getUrlMatcher($container));
            },
            static::EVENT_PRIORITY_KERNEL_REQUEST
        );

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    protected function addFinishRequestKernelEventListener(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::FINISH_REQUEST,
            function (FinishRequestEvent $event) use ($container) {
                $requestStack = $this->getRequestStack($container);
                $parentRequest = $requestStack->getParentRequest();
                if ($parentRequest !== null) {
                    $this->setRouterContext($parentRequest, $this->getUrlMatcher($container));
                }
            },
            static::EVENT_PRIORITY_KERNEL_FINISH_REQUEST
        );

        return $eventDispatcher;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Routing\RequestContextAwareInterface $router
     *
     * @return \Symfony\Component\Routing\RequestContextAwareInterface
     */
    protected function setRouterContext(Request $request, RequestContextAwareInterface $router): RequestContextAwareInterface
    {
        $router->getContext()->setParameter('_locale', $request->getLocale());

        return $router;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Routing\RequestContextAwareInterface
     */
    protected function getUrlMatcher(ContainerInterface $container): RequestContextAwareInterface
    {
        return $container->get(static::SERVICE_URL_MATCHER);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    protected function getRequestStack(ContainerInterface $container): RequestStack
    {
        return $container->get(static::SERVICE_REQUEST_STACK);
    }
}
