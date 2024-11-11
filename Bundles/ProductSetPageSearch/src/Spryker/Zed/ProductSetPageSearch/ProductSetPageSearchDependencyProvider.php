<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetPageSearch;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToEventBehaviorFacadeBridge;
use Spryker\Zed\ProductSetPageSearch\Dependency\Facade\ProductSetPageSearchToProductSetBridge;
use Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer\ProductSetPageSearchToProductImageQueryContainerBridge;
use Spryker\Zed\ProductSetPageSearch\Dependency\QueryContainer\ProductSetPageSearchToProductSetQueryContainerBridge;
use Spryker\Zed\ProductSetPageSearch\Dependency\Service\ProductSetPageSearchToUtilEncodingBridge;

/**
 * @method \Spryker\Zed\ProductSetPageSearch\ProductSetPageSearchConfig getConfig()
 */
class ProductSetPageSearchDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SYNCHRONIZATION = 'SERVICE_UTIL_SYNCHRONIZATION';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_SET = 'FACADE_PRODUCT_SET';

    /**
     * @var string
     */
    public const FACADE_EVENT_BEHAVIOR = 'FACADE_EVENT_BEHAVIOR';

    /**
     * @var string
     */
    public const SERVICE_UTIL_SANITIZE = 'SERVICE_UTIL_SANITIZE';

    /**
     * @var string
     */
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container->set(static::FACADE_EVENT_BEHAVIOR, function (Container $container) {
            return new ProductSetPageSearchToEventBehaviorFacadeBridge($container->getLocator()->eventBehavior()->facade());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return new ProductSetPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addUtilEncodingService($container);
        $container = $this->addProductSetFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT_SET, function (Container $container) {
            return new ProductSetPageSearchToProductSetQueryContainerBridge($container->getLocator()->productSet()->queryContainer());
        });

        $container->set(static::QUERY_CONTAINER_PRODUCT_IMAGE, function (Container $container) {
            return new ProductSetPageSearchToProductImageQueryContainerBridge($container->getLocator()->productImage()->queryContainer());
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
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductSetPageSearchToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductSetFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_SET, function (Container $container) {
            return new ProductSetPageSearchToProductSetBridge($container->getLocator()->productSet()->facade());
        });

        return $container;
    }
}
