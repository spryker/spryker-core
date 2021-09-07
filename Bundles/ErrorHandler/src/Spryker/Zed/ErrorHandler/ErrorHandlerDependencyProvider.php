<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ErrorHandler;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\ErrorHandler\ErrorHandlerConfig getConfig()
 */
class ErrorHandlerDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_EXCEPTION_HANDLER = 'PLUGINS_EXCEPTION_HANDLER';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_KERNEL
     * @var string
     */
    public const SERVICE_KERNEL = 'kernel';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addRequestStack($container);
        $container = $this->addKernel($container);
        $container = $this->addExceptionHandlerStrategyPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addKernel(Container $container): Container
    {
        $container->set(static::SERVICE_KERNEL, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_KERNEL);
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addExceptionHandlerStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_EXCEPTION_HANDLER, function () {
            return $this->getExceptionHandlerStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\ErrorHandlerExtension\Dependency\Plugin\ExceptionHandlerStrategyPluginInterface[]
     */
    protected function getExceptionHandlerStrategyPlugins(): array
    {
        return [];
    }
}
