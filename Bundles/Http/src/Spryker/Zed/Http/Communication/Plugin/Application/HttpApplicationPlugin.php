<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Http\Communication\Plugin\Application;

use ArrayObject;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\RequestContext;

/**
 * @method \Spryker\Zed\Http\Communication\HttpCommunicationFactory getFactory()
 * @method \Spryker\Zed\Http\HttpConfig getConfig()
 */
class HttpApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_COOKIES = 'cookies';
    protected const SERVICE_KERNEL = 'kernel';
    protected const SERVICE_REQUEST_STACK = 'request_stack';
    protected const SERVICE_REQUEST_CONTEXT = 'request_context';
    protected const SERVICE_CONTROLLER_RESOLVER = 'resolver';

    /**
     * @uses \Spryker\Zed\EventDispatcher\Communication\Plugin\Application\EventDispatcherApplicationPlugin::SERVICE_DISPATCHER
     */
    protected const SERVICE_EVENT_DISPATCHER = 'dispatcher';

    /**
     * {@inheritDoc}
     * - Sets trusted proxies and host.
     * - Sets `cookies` service identifier.
     * - Adds `HttpKernel` as a `kernel` service to the container.
     * - Adds `RequestStack` as a `request_stack` service to the container.
     * - Adds `RequestContext` as a `request_context` service to the container.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $this->setTrustedProxies();
        $this->setTrustedHosts();

        $container = $this->addKernelService($container);
        $container = $this->addRequestStackService($container);
        $container = $this->addRequestContextService($container);
        $container = $this->addCookie($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addKernelService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_KERNEL, function (ContainerInterface $container) {
            return new HttpKernel(
                $this->getEventDispatcher($container),
                $this->getResolver($container),
                $this->getRequestStack($container)
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addRequestStackService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_REQUEST_STACK, function () {
            return new RequestStack();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addRequestContextService(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_REQUEST_CONTEXT, function () {
            $context = new RequestContext();

            $context->setHttpPort($this->getConfig()->getHttpPort());
            $context->setHttpsPort($this->getConfig()->getHttpsPort());

            return $context;
        });

        return $container;
    }

    /**
     * @return void
     */
    protected function setTrustedProxies(): void
    {
        Request::setTrustedProxies($this->getConfig()->getTrustedProxies(), $this->getConfig()->getTrustedHeaderSet());
    }

    /**
     * @return void
     */
    protected function setTrustedHosts(): void
    {
        Request::setTrustedHosts($this->getConfig()->getTrustedHosts());
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addCookie(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_COOKIES, function () {
            return new ArrayObject();
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    protected function getEventDispatcher(ContainerInterface $container): EventDispatcherInterface
    {
        return $container->get(static::SERVICE_EVENT_DISPATCHER);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface
     */
    protected function getResolver(ContainerInterface $container): ControllerResolverInterface
    {
        return $container->get(static::SERVICE_CONTROLLER_RESOLVER);
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
