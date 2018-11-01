<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipStorage;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipToCompanyBusinessUnitQuery;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductStoreQuery;
use Orm\Zed\PriceProductMerchantRelationship\Persistence\SpyPriceProductMerchantRelationshipQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToPriceProductFacadeBridge;
use Spryker\Zed\PriceProductMerchantRelationshipStorage\Dependency\Facade\PriceProductMerchantRelationshipStorageToStoreFacadeBridge;

class PriceProductMerchantRelationshipStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PROPEL_QUERY_PRICE_PRODUCT_STORE = 'PROPEL_QUERY_PRICE_PRODUCT_STORE';
    public const PROPEL_QUERY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP = 'PROPEL_QUERY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP';
    public const PROPEL_QUERY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT = 'PROPEL_QUERY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT';

    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addPriceProductFacade($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addEventBehaviorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = $this->addPropelPriceProductStoreQuery($container);
        $container = $this->addPropelPriceProductMerchantRelationshipQuery($container);
        $container = $this->addPropelMerchantRelationshipToCompanyBusinessUnitQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container[static::FACADE_EVENT_BEHAVIOR] = function (Container $container) {
            return new PriceProductMerchantRelationshipStorageToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelPriceProductStoreQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_PRODUCT_STORE] = function () {
            return SpyPriceProductStoreQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelPriceProductMerchantRelationshipQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRICE_PRODUCT_MERCHANT_RELATIONSHIP] = function () {
            return SpyPriceProductMerchantRelationshipQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelMerchantRelationshipToCompanyBusinessUnitQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_MERCHANT_RELATIONSHIP_TO_COMPANY_BUSINESS_UNIT] = function () {
            return SpyMerchantRelationshipToCompanyBusinessUnitQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new PriceProductMerchantRelationshipStorageToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new PriceProductMerchantRelationshipStorageToStoreFacadeBridge(
                $container->getLocator()->store()->facade()
            );
        };

        return $container;
    }
}
