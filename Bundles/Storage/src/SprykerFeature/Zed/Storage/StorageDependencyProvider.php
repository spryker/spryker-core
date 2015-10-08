<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Storage;

use SprykerEngine\Zed\Kernel\AbstractBundleDependencyProvider;
use SprykerEngine\Zed\Kernel\Container;

class StorageDependencyProvider extends AbstractBundleDependencyProvider
{

    const CLIENT_STORAGE = 'storage client';
    const FACADE_COLLECTOR = 'collector facade';

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param Container $container
     *
     * @return Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->addStorageClient($container);
        $this->addCollectorFacade($container);

        return $container;
    }

    /**
     * @param Container $container
     */
    protected function addStorageClient(Container $container)
    {
        $container[self::CLIENT_STORAGE] = function (Container $container) {
            return $container->getLocator()->storage()->client();
        };
    }

    /**
     * @param $container
     */
    protected function addCollectorFacade($container)
    {
        $container[self::FACADE_COLLECTOR] = function (Container $container) {
            return $container->getLocator()->collector()->facade();
        };
    }

}
