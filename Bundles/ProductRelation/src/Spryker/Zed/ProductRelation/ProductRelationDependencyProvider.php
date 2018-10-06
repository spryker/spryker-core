<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToLocaleBridge;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToMoneyBridge;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToPriceProductFacadeBridge;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToProductBridge as FacadeProductRelationToProductBridge;
use Spryker\Zed\ProductRelation\Dependency\Facade\ProductRelationToTouchBridge;
use Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToProductBridge as QueryContainerProductRelationToProductBridge;
use Spryker\Zed\ProductRelation\Dependency\QueryContainer\ProductRelationToPropelQueryBuilderBridge;
use Spryker\Zed\ProductRelation\Dependency\Service\ProductRelationToUtilEncodingBridge;

class ProductRelationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'locale facade';
    public const FACADE_TOUCH = 'touch facade';
    public const FACADE_MONEY = 'money facade';
    public const FACADE_PRODUCT = 'product facade';
    public const FACADE_PRICE_PRODUCT = 'price product facade';

    public const QUERY_CONTAINER_PRODUCT = 'product query container';
    public const QUERY_CONTAINER_PROPEL_QUERY_BUILDER = 'query propel rule container';

    public const SERVICE_UTIL_ENCODING = 'util encoding service';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addFacadeLocale($container);
        $container = $this->addFacadeTouch($container);

        $container = $this->addServiceUtilEncoding($container);

        return $container;
    }

     /**
      * @param \Spryker\Zed\Kernel\Container $container
      *
      * @return \Spryker\Zed\Kernel\Container
      */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFacadeLocale($container);
        $container = $this->addFacadeMoney($container);
        $container = $this->addFacadeProduct($container);
        $container = $this->addPriceProductFacade($container);

        $container = $this->addServiceUtilEncoding($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addQueryContainerProduct($container);
        $container = $this->addQueryContainerPropelQueryBuilder($container);

        $container = $this->addFacadeLocale($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerProduct(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new QueryContainerProductRelationToProductBridge(
                $container->getLocator()->product()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerPropelQueryBuilder(Container $container)
    {
        $container[static::QUERY_CONTAINER_PROPEL_QUERY_BUILDER] = function (Container $container) {
            return new ProductRelationToPropelQueryBuilderBridge(
                $container->getLocator()->propelQueryBuilder()->queryContainer()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeLocale(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductRelationToLocaleBridge(
                $container->getLocator()->locale()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeMoney(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ProductRelationToMoneyBridge(
                $container->getLocator()->money()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addServiceUtilEncoding(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductRelationToUtilEncodingBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeProduct(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new FacadeProductRelationToProductBridge(
                $container->getLocator()->product()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeTouch(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new ProductRelationToTouchBridge(
                $container->getLocator()->touch()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container)
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductRelationToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        };

        return $container;
    }
}
