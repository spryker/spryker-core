<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerStorage;

use Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientBridge;
use Spryker\Client\CustomerStorage\Dependency\Client\CustomerStorageToStorageClientInterface;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceBridge;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToSynchronizationServiceInterface;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceBridge;
use Spryker\Client\CustomerStorage\Dependency\Service\CustomerStorageToUtilEncodingServiceInterface;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CustomerStorageDependencyProvider extends AbstractDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORAGE = 'CLIENT_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const SERVICE_SYNCHRONIZATION = 'SERVICE_SYNCHRONIZATION';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = parent::provideServiceLayerDependencies($container);
        $container = $this->addStorageClient($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addSynchronizationService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE, function (Container $container): CustomerStorageToStorageClientInterface {
            return new CustomerStorageToStorageClientBridge($container->getLocator()->storage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): CustomerStorageToUtilEncodingServiceInterface {
            return new CustomerStorageToUtilEncodingServiceBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addSynchronizationService(Container $container): Container
    {
        $container->set(static::SERVICE_SYNCHRONIZATION, function (Container $container): CustomerStorageToSynchronizationServiceInterface {
            return new CustomerStorageToSynchronizationServiceBridge($container->getLocator()->synchronization()->service());
        });

        return $container;
    }
}
