<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantConnector;

use Orm\Zed\SalesMerchantConnector\Persistence\SpySalesOrderMerchantQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantFacadeBridge;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToMerchantProductOfferFacadeBridge;
use Spryker\Zed\SalesMerchantConnector\Dependency\Facade\SalesMerchantConnectorToStoreFacadeBridge;

/**
 * @method \Spryker\Zed\SalesMerchantConnector\SalesMerchantConnectorConfig getConfig()
 */
class SalesMerchantConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_PRODUCT_OFFER = 'FACADE_MERCHANT_PRODUCT_OFFER';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const FACADE_STORE = 'FACADE_STORE';

    public const PROPEL_QUERY_SALES_ORDER_MERCHANT = 'PROPEL_QUERY_SALES_ORDER_MERCHANT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addMerchantProductOfferFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addSalesOrderMerchantPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMerchantProductOfferFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT_OFFER, function (Container $container) {
            return new SalesMerchantConnectorToMerchantProductOfferFacadeBridge(
                $container->getLocator()->merchantProductOffer()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new SalesMerchantConnectorToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new SalesMerchantConnectorToMerchantFacadeBridge(
                $container->getLocator()->merchant()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addSalesOrderMerchantPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_MERCHANT, function (Container $container) {
            return SpySalesOrderMerchantQuery::create();
        });

        return $container;
    }
}
