<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Storage;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\Storage\Dependency\Client\StorageToStorageClientBridge;

class StorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function ($container) {
            return new StorageToStorageClientBridge(
                $container->getLocator()->storage()->client()
            );
        });

        return $container;
    }
}
