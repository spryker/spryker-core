<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderMatrixGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OrderMatrixGui\Dependency\Facade\OrderMatrixGuiToOrderMatrixFacadeBridge;
use Spryker\Zed\OrderMatrixGui\Dependency\Service\OrderMatrixGuiToUtilSanitizeServiceBridge;

/**
 * @method \Spryker\Zed\OrderMatrixGui\OrderMatrixGuiConfig getConfig()
 */
class OrderMatrixGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_ORDER_MATRIX = 'FACADE_ORDER_MATRIX';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addOrderMatrixFacade($container);
        $container = $this->addUtilSanitizeService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOrderMatrixFacade(Container $container): Container
    {
        $container->set(static::FACADE_ORDER_MATRIX, function (Container $container) {
            return new OrderMatrixGuiToOrderMatrixFacadeBridge($container->getLocator()->orderMatrix()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilSanitizeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_SANITIZE, function (Container $container) {
            return new OrderMatrixGuiToUtilSanitizeServiceBridge($container->getLocator()->utilSanitize()->service());
        });

        return $container;
    }
}
