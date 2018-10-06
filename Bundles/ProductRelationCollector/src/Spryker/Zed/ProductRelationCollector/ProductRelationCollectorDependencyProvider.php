<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationCollector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToCollectorBridge;
use Spryker\Zed\ProductRelationCollector\Dependency\Facade\ProductRelationCollectorToPriceProductBridgeProductFacade;
use Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorCollectorToProductImageBridge;
use Spryker\Zed\ProductRelationCollector\Dependency\QueryContainer\ProductRelationCollectorToProductRelationBridge;

class ProductRelationCollectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COLLECTOR = 'FACADE_COLLECTOR';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    public const SERVICE_DATA_READER = 'SERVICE_DATA_READER';

    public const QUERY_CONTAINER_TOUCH = 'QUERY_CONTAINER_TOUCH';
    public const QUERY_CONTAINER_PRODUCT_IMAGE = 'QUERY_CONTAINER_PRODUCT_IMAGE';
    public const QUERY_CONTAINER_PRODUCT_RELATION = 'QUERY_CONTAINER_PRODUCT_RELATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->provideCollectorFacade($container);
        $container = $this->providePriceFacade($container);

        $container = $this->provideDataReaderService($container);

        $container = $this->provideTouchQueryContainer($container);
        $container = $this->provideProductRelationQueryContainer($container);
        $container = $this->provideProductImageQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideCollectorFacade(Container $container)
    {
        $container[static::FACADE_COLLECTOR] = function (Container $container) {
            return new ProductRelationCollectorToCollectorBridge($container->getLocator()->collector()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function providePriceFacade(Container $container)
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductRelationCollectorToPriceProductBridgeProductFacade($container->getLocator()->priceProduct()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideDataReaderService(Container $container)
    {
        $container[static::SERVICE_DATA_READER] = function (Container $container) {
            return $container->getLocator()->utilDataReader()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideTouchQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_TOUCH] = function (Container $container) {
            return $container->getLocator()->touch()->queryContainer();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductRelationQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_RELATION] = function (Container $container) {
            return new ProductRelationCollectorToProductRelationBridge(
                $container->getLocator()->productRelation()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function provideProductImageQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductRelationCollectorCollectorToProductImageBridge(
                $container->getLocator()->productImage()->queryContainer()
            );
        };

        return $container;
    }
}
