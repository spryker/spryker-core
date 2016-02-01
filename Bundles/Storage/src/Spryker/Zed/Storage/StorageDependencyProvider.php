<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Storage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Storage\Dependency\Facade\StorageToCollectorBridge;

class StorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_STORAGE = 'storage client';
    const FACADE_COLLECTOR = 'collector facade';

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addStorageClient($container);
        $this->addCollectorFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return void
     */
    protected function addStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };
    }

    /**
     * @param $container
     *
     * @return void
     */
    protected function addCollectorFacade($container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return new StorageToCollectorBridge($container->getLocator()->collector()->facade());
        };
    }

}
