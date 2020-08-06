<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToAvailabilityFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToLocaleFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToMessengerFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToPriceProductFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToProductImageFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStockFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\Facade\ProductBundleToStoreFacadeBridge;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToProductQueryContainerBridge;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToSalesQueryContainerBridge;
use Spryker\Zed\ProductBundle\Dependency\QueryContainer\ProductBundleToStockQueryContainerBridge;

/**
 * @method \Spryker\Zed\ProductBundle\ProductBundleConfig getConfig()
 */
class ProductBundleDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT = 'FACADE_PRODUCT';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_AVAILABILITY = 'FACADE_AVAILABILITY';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const FACADE_STORE = 'FACADE_STORE';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_STOCK = 'FACADE_STOCK';

    public const QUERY_CONTAINER_SALES = 'QUERY_CONTAINER_SALES';
    public const QUERY_CONTAINER_STOCK = 'QUERY_CONTAINER_STOCK';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addFacadeProduct($container);
        $container = $this->addFacadePriceProduct($container);
        $container = $this->addFacadeLocale($container);
        $container = $this->addFacadeAvailability($container);
        $container = $this->addFacadeProductImage($container);
        $container = $this->addFacadePrice($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addStockFacade($container);

        $container = $this->addQueryContainerSales($container);
        $container = $this->addQueryContainerStock($container);
        $container = $this->addQueryContainerProduct($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeProduct(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT, function (Container $container) {
            return new ProductBundleToProductFacadeBridge($container->getLocator()->product()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadePriceProduct(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new ProductBundleToPriceProductFacadeBridge($container->getLocator()->priceProduct()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeLocale(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductBundleToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeAvailability(Container $container): Container
    {
        $container->set(static::FACADE_AVAILABILITY, function (Container $container) {
            return new ProductBundleToAvailabilityFacadeBridge($container->getLocator()->availability()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerSales(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_SALES, function (Container $container) {
            return new ProductBundleToSalesQueryContainerBridge($container->getLocator()->sales()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerStock(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_STOCK, function (Container $container) {
            return new ProductBundleToStockQueryContainerBridge($container->getLocator()->stock()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueryContainerProduct(Container $container): Container
    {
        $container->set(static::QUERY_CONTAINER_PRODUCT, function (Container $container) {
            return new ProductBundleToProductQueryContainerBridge($container->getLocator()->product()->queryContainer());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadeProductImage(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_IMAGE, function (Container $container) {
            return new ProductBundleToProductImageFacadeBridge($container->getLocator()->productImage()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFacadePrice(Container $container): Container
    {
        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new ProductBundleToPriceFacadeBridge($container->getLocator()->price()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return new ProductBundleToStoreFacadeBridge($container->getLocator()->store()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container->set(static::FACADE_MESSENGER, function (Container $container) {
            return new ProductBundleToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_STOCK, function (Container $container) {
            return new ProductBundleToStockFacadeBridge(
                $container->getLocator()->stock()->facade()
            );
        });

        return $container;
    }
}
