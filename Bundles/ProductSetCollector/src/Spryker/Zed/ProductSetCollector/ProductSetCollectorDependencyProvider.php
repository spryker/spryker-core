<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetCollector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToCollectorBridge;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToProductSetBridge;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToSearchBridge;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToStoreFacadeBridge;
use Spryker\Zed\ProductSetCollector\Dependency\Facade\ProductSetCollectorToStoreFacadeInterface;

/**
 * @method \Spryker\Zed\ProductSetCollector\ProductSetCollectorConfig getConfig()
 */
class ProductSetCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';

    /**
     * @var string
     */
    public const FACADE_SEARCH = 'FACADE_SEARCH';

    /**
     * @var string
     */
    public const FACADE_STORE = 'FACADE_STORE';

    /**
     * @var string
     */
    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    /**
     * @var string
     */
    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $this->addCollectorFacade($container);
        $this->addSearchFacade($container);
        $this->addProductSetFacade($container);
        $this->addDataReaderService($container);
        $this->addTouchQueryContainer($container);
        $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addCollectorFacade(Container $container)
    {
        $container->set(static::FACADE_COLLECTOR, function (Container $container) {
            return new ProductSetCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addSearchFacade(Container $container)
    {
        $container->set(static::FACADE_SEARCH, function (Container $container) {
            return new ProductSetCollectorToSearchBridge($container->getLocator()->search()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addProductSetFacade(Container $container)
    {
        $container->set(static::FACADE_PRODUCT_SET, function (Container $container) {
            return new ProductSetCollectorToProductSetBridge($container->getLocator()->productSet()->facade());
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addDataReaderService(Container $container)
    {
        $container->set(static::SERVICE_DATA_READER, function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addTouchQueryContainer(Container $container)
    {
        $container->set(static::QUERY_CONTAINER_TOUCH, function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        });
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function addStoreFacade(Container $container): void
    {
        $container->set(static::FACADE_STORE, function (Container $container): ProductSetCollectorToStoreFacadeInterface {
            return new ProductSetCollectorToStoreFacadeBridge($container->getLocator()->store()->facade());
        });
    }
}
