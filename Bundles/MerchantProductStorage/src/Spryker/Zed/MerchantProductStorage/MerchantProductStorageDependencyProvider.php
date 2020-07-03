<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductStorage;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToEventBehaviorFacadeBridge;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToMerchantProductFacadeBridge;
use Spryker\Zed\MerchantProductStorage\Dependency\Facade\MerchantProductStorageToProductStorageFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantProductStorage\MerchantProductStorageConfig getConfig()
 */
class MerchantProductStorageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';
    public const FACADE_MERCHANT_PRODUCT = 'FACADE_MERCHANT_PRODUCT';
    public const FACADE_PRODUCT_STORAGE = 'FACADE_PRODUCT_STORAGE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addEventBehaviorFacade($container);
        $container = $this->addMerchantProductFacade($container);
        $container = $this->addProductStorageFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addMerchantProductFacade($container);

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
            return new MerchantProductStorageToEventBehaviorFacadeBridge(
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
    protected function addMerchantProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_PRODUCT, function (Container $container) {
            return new MerchantProductStorageToMerchantProductFacadeBridge(
                $container->getLocator()->merchantProduct()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductStorageFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_STORAGE, function (Container $container) {
            return new MerchantProductStorageToProductStorageFacadeBridge(
                $container->getLocator()->productStorage()->facade()
            );
        });

        return $container;
    }
}
