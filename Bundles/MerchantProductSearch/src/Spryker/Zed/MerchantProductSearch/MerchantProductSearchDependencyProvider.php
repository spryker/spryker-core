<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductSearch;

use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\MerchantProductSearch\Dependency\Facade\MerchantProductSearchToProductPageSearchFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductSearch\MerchantProductSearchConfig getConfig()
 */
class MerchantProductSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_PAGE_SEARCH = 'FACADE_PRODUCT_PAGE_SEARCH';
    public const PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT = 'PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductPageSearchFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addMerchantProductAbstractPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addEventBehaviorFacade(Container $container): Container
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new MerchantProductSearchToEventBehaviorFacadeBridge(
                $container->getLocator()->eventBehavior()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductPageSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_PAGE_SEARCH, function (Container $container) {
            return new MerchantProductSearchToProductPageSearchFacadeBridge(
                $container->getLocator()->productPageSearch()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductAbstractPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_PRODUCT_ABSTRACT, $container->factory(function () {
            return SpyMerchantProductAbstractQuery::create();
        }));

        return $container;
    }
}
