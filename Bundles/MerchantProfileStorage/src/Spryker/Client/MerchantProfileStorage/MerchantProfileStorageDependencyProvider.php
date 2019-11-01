<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientBridge;
use Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceBridge;

/**
 * @method \Spryker\Zed\MerchantProfileStorage\MerchantProfileStorageConfig getConfig()
 */
class MerchantProfileStorageDependencyProvider extends AbstractDependencyProvider
{
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addSynchronizationService($container);
        $container = $this->addStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container) {
            return new MerchantProfileStorageConnectorToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container) {
            return new MerchantProfileStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }
}
