<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToLocaleBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToMoneyBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToPriceProductBridgeProductFacade;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductImageBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToProductSetBridge;
use Spryker\Zed\ProductSetGui\Dependency\Facade\ProductSetGuiToUrlBridge;
use Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductBridge;
use Spryker\Zed\ProductSetGui\Dependency\QueryContainer\ProductSetGuiToProductSetBridge as ProductSetGuiToProductSetQueryContainerBridge;
use Spryker\Zed\ProductSetGui\Dependency\Service\ProductSetGuiToUtilEncodingBridge;

class ProductSetGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRODUCT_SET = 'FACADE_ProductSet';
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_URL = 'FACADE_URL';
    public const FACADE_PRODUCT_IMAGE = 'FACADE_PRODUCT_IMAGE';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE';
    public const FACADE_MONEY = 'FACADE_MONEY';

    public const QUERY_CONTAINER_PRODUCT_SET = 'QUERY_CONTAINER_PRODUCT_SET';
    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $this->provideProductSetFacade($container);
        $this->provideLocaleFacade($container);
        $this->provideUrlFacade($container);
        $this->provideProductImageFacade($container);
        $this->providePriceProductFacade($container);
        $this->provideMoneyFacade($container);

        $this->provideUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $this->provideProductSetQueryContainer($container);
        $this->provideProductQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_SET] = function (Container $container) {
            return new ProductSetGuiToProductSetBridge($container->getLocator()->productSet()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductSetGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUrlFacade(Container $container)
    {
        $container[static::FACADE_URL] = function (Container $container) {
            return new ProductSetGuiToUrlBridge($container->getLocator()->url()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductImageFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_IMAGE] = function (Container $container) {
            return new ProductSetGuiToProductImageBridge($container->getLocator()->productImage()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function providePriceProductFacade(Container $container)
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductSetGuiToPriceProductBridgeProductFacade($container->getLocator()->priceProduct()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ProductSetGuiToMoneyBridge($container->getLocator()->money()->facade());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductSetQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT_SET] = function (Container $container) {
            return new ProductSetGuiToProductSetQueryContainerBridge($container->getLocator()->productSet()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductSetGuiToProductBridge($container->getLocator()->product()->queryContainer());
        };
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return void
     */
    protected function provideUtilEncodingService(Container $container)
    {
        $container[static::SERVICE_UTIL_ENCODING] = function (Container $container) {
            return new ProductSetGuiToUtilEncodingBridge($container->getLocator()->utilEncoding()->service());
        };
    }
}
