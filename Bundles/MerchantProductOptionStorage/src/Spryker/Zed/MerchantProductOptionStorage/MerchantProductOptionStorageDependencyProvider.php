<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOptionStorage;

use Orm\Zed\MerchantProductOption\Persistence\SpyMerchantProductOptionGroupQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToMerchantProductOptionFacadeBridge;
use Spryker\Zed\MerchantProductOptionStorage\Dependency\Facade\MerchantProductOptionStorageToProductOptionStorageFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductOptionStorage\MerchantProductOptionStorageConfig getConfig()
 */
class MerchantProductOptionStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_PRODUCT_OPTION = 'FACADE_MERCHANT_PRODUCT_OPTION';
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_PRODUCT_OPTION_STORAGE = 'FACADE_PRODUCT_OPTION_STORAGE';

    public const PROPEL_QUERY_MERCHANT_PRODUCT_OPTION_GROUP = 'PROPEL_QUERY_MERCHANT_PRODUCT_OPTION_GROUP';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addMerchantProductOptionGroupPropelQuery($container);

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
        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addProductOptionStorageFacade($container);
        $container = $this->addMerchantProductOptionFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductOptionGroupPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_PRODUCT_OPTION_GROUP, $container->factory(function () {
            return SpyMerchantProductOptionGroupQuery::create();
        }));

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
            return new MerchantProductOptionStorageToEventBehaviorFacadeBridge(
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
    protected function addProductOptionStorageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_OPTION_STORAGE, function (Container $container) {
            return new MerchantProductOptionStorageToProductOptionStorageFacadeBridge(
                $container->getLocator()->productOptionStorage()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantProductOptionFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT_OPTION, function (Container $container) {
            return new MerchantProductOptionStorageToMerchantProductOptionFacadeBridge(
                $container->getLocator()->merchantProductOption()->facade()
            );
        });

        return $container;
    }
}
