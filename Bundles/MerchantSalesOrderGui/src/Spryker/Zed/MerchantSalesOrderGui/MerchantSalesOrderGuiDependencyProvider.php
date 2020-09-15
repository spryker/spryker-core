<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantSalesOrderFacadeBridge;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToMerchantUserFacadeBridge;
use Spryker\Zed\MerchantSalesOrderGui\Dependency\Facade\MerchantSalesOrderGuiToShipmentFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantSalesOrderGui\MerchantSalesOrderGuiConfig getConfig()
 */
class MerchantSalesOrderGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_SHIPMENT = 'FACADE_SHIPMENT';
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';
    public const FACADE_MERCHANT_SALES_ORDER = 'FACADE_MERCHANT_SALES_ORDER';

    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantSalesOrderFacade($container);
        $container = $this->addMerchantUserFacades($container);
        $container = $this->addShipmentFacade($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantSalesOrderFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_SALES_ORDER, function (Container $container) {
            return new MerchantSalesOrderGuiToMerchantSalesOrderFacadeBridge(
                $container->getLocator()->merchantSalesOrder()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return new MerchantSalesOrderGuiToMerchantUserFacadeBridge(
                $container->getLocator()->merchantUser()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addShipmentFacade(Container $container): Container
    {
        $container->set(static::FACADE_SHIPMENT, function (Container $container) {
            return new MerchantSalesOrderGuiToShipmentFacadeBridge(
                $container->getLocator()->shipment()->facade()
            );
        });

        return $container;
    }
}
