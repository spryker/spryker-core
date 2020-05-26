<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOms;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesOms\Dependency\Facade\SalesOmsToOmsFacadeBridge;
use Spryker\Zed\SalesOms\Dependency\Service\SalesOmsToUtilDataReaderServiceBridge;

/**
 * @method \Spryker\Zed\SalesOms\SalesOmsConfig getConfig()
 */
class SalesOmsDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_UTIL_DATA_READER = 'SERVICE_UTIL_DATA_READER';
    public const FACADE_OMS = 'FACADE_OMS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addUtilDataReaderService($container);
        $container = $this->addOmsFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDataReaderService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATA_READER, function (Container $container) {
            return new SalesOmsToUtilDataReaderServiceBridge($container->getLocator()->utilDataReader()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOmsFacade(Container $container): Container
    {
        $container->set(static::FACADE_OMS, function (Container $container) {
            return new SalesOmsToOmsFacadeBridge($container->getLocator()->oms()->facade());
        });

        return $container;
    }
}
