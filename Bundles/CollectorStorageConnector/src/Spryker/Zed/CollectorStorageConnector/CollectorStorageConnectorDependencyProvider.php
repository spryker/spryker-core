<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CollectorStorageConnector;

use Spryker\Zed\CollectorStorageConnector\Dependency\Facade\CollectorStorageConnectorToCollectorBridge;
use Spryker\Zed\CollectorStorageConnector\Dependency\Facade\CollectorStorageConnectorToStorageBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CollectorStorageConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_STORAGE = 'storage facade';
    public const FACADE_COLLECTOR = 'collector facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addStorageFacade($container);
        $container = $this->addCollectorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addStorageFacade(Container $container)
    {
        $container[self::FACADE_STORAGE] = function (Container $container) {
            return new CollectorStorageConnectorToStorageBridge($container->getLocator()->storage()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    private function addCollectorFacade(Container $container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new CollectorStorageConnectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }
}
