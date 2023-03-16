<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthWarehouse;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeBridge;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToOauthFacadeInterface;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToStockFacadeBridge;
use Spryker\Zed\OauthWarehouse\Dependency\Facade\OauthWarehouseToStockFacadeInterface;
use Spryker\Zed\OauthWarehouse\Dependency\Service\OauthWarehouseToUtilEncodingServiceBridge;
use Spryker\Zed\OauthWarehouse\Dependency\Service\OauthWarehouseToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Zed\OauthWarehouse\OauthWarehouseConfig getConfig()
 */
class OauthWarehouseDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @var string
     */
    public const FACADE_STOCK = 'FACADE_STOCK';

    /**
     * @var string
     */
    public const FACADE_OAUTH = 'FACADE_OAUTH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addOauthFacade($container);
        $container = $this->addStockFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_STOCK, function (Container $container): OauthWarehouseToStockFacadeInterface {
            return new OauthWarehouseToStockFacadeBridge(
                $container->getLocator()->stock()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addOauthFacade(Container $container): Container
    {
        $container->set(static::FACADE_OAUTH, function (Container $container): OauthWarehouseToOauthFacadeInterface {
            return new OauthWarehouseToOauthFacadeBridge(
                $container->getLocator()->oauth()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): OauthWarehouseToUtilEncodingServiceInterface {
            return new OauthWarehouseToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
