<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\Router\Resolver\ControllerResolver;

/**
 * @method \Spryker\Yves\Router\RouterFactory getFactory()
 */
class RouterApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * @see \Spryker\Shared\Application\Application::SERVICE_REQUEST_STACK
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';
    public const SERVICE_ROUTER = 'routers';
    public const SERVICE_CONTROLLER_RESOLVER = 'controller-resolver';
    public const SERVICE_ARGUMENT_RESOLVER = 'argument-resolver';

    /**
     * Specification:
     * - Adds a ChainRouter to the Application.
     * - Adds a ControllerResolver to the Application which is used in the HttpKernel.
     * - Adds an ArgumentResolver to the Application which is used in the HttpKernel.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->provideRouter($container);
        $container = $this->provideControllerResolver($container);
        $container = $this->provideArgumentResolver($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function provideRouter(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_ROUTER, function () {
            return $this->getFactory()->createRouter();
        });

        $this->setBackwardsCompatibleServiceNames($container, static::SERVICE_ROUTER, [
            'alias' => [
                'url_generator',
                'url_matcher',
            ],
        ]);

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $serviceName
     * @param array $configuration
     *
     * @return void
     */
    protected function setBackwardsCompatibleServiceNames(ContainerInterface $container, string $serviceName, array $configuration): void
    {
        $container->configure($serviceName, $configuration);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function provideControllerResolver(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_CONTROLLER_RESOLVER, function () use ($container) {
            return new ControllerResolver($container);
        });

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function provideArgumentResolver(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_ARGUMENT_RESOLVER, function () {
            return $this->getFactory()->createArgumentResolver();
        });

        return $container;
    }
}
