<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteCheckoutConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteCheckoutConnector\Dependency\Client\QuoteCheckoutConnectorToStorageRedisClientBridge;
use Spryker\Zed\QuoteCheckoutConnector\Dependency\Service\QuoteCheckoutConnectorToUtilTextServiceBridge;

/**
 * @method \Spryker\Zed\QuoteCheckoutConnector\QuoteCheckoutConnectorConfig getConfig()
 */
class QuoteCheckoutConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_STORAGE_REDIS = 'CLIENT_STORAGE_REDIS';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStorageRedisClient($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStorageRedisClient(Container $container): Container
    {
        $container->set(static::CLIENT_STORAGE_REDIS, function (Container $container) {
            return new QuoteCheckoutConnectorToStorageRedisClientBridge($container->getLocator()->storageRedis()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new QuoteCheckoutConnectorToUtilTextServiceBridge(
                $container->getLocator()->utilText()->service(),
            );
        });

        return $container;
    }
}
