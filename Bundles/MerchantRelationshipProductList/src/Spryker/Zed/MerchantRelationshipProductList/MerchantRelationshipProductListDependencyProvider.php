<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductList;

use Orm\Zed\ProductList\Persistence\SpyProductListQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeBridge;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToMerchantRelationshipFacadeInterface;
use Spryker\Zed\MerchantRelationshipProductList\Dependency\Facade\MerchantRelationshipProductListToProductListFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductList\MerchantRelationshipProductListConfig getConfig()
 */
class MerchantRelationshipProductListDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_LIST = 'FACADE_PRODUCT_LIST';
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';

    public const PROPEL_QUERY_PRODUCT_LIST = 'PROPEL_QUERY_PRODUCT_LIST';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addProductListPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addProductListFacade($container);
        $container = $this->addMerchantRelationshipFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_PRODUCT_LIST] = function (Container $container) {
            return SpyProductListQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductListFacade(Container $container): Container
    {
        $container[static::FACADE_PRODUCT_LIST] = function (Container $container) {
            return new MerchantRelationshipProductListToProductListFacadeBridge(
                $container->getLocator()->productList()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP] = function (Container $container): MerchantRelationshipProductListToMerchantRelationshipFacadeInterface {
            return new MerchantRelationshipProductListToMerchantRelationshipFacadeBridge(
                $container->getLocator()->merchantRelationship()->facade()
            );
        };

        return $container;
    }
}
