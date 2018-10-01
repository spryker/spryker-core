<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToLocaleBridge;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToMoneyBridge;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToPriceProductFacadeBridge;
use Spryker\Zed\ProductLabelGui\Dependency\Facade\ProductLabelGuiToProductLabelBridge;
use Spryker\Zed\ProductLabelGui\Dependency\QueryContainer\ProductLabelGuiToProductQueryContainerBridge;

class ProductLabelGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_LOCALE = 'FACADE_LOCALE';
    public const FACADE_PRODUCT_LABEL = 'FACADE_PRODUCT_LABEL';
    public const FACADE_MONEY = 'FACADE_MONEY';
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    public const QUERY_CONTAINER_PRODUCT = 'QUERY_CONTAINER_PRODUCT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addLocaleFacade($container);
        $container = $this->addProductLabelFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addProductQueryContainer($container);
        $container = $this->addPriceProductFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addProductLabelFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container)
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new ProductLabelGuiToLocaleBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductLabelFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT_LABEL] = function (Container $container) {
            return new ProductLabelGuiToProductLabelBridge($container->getLocator()->productLabel()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container)
    {
        $container[static::FACADE_MONEY] = function (Container $container) {
            return new ProductLabelGuiToMoneyBridge($container->getLocator()->money()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_PRODUCT] = function (Container $container) {
            return new ProductLabelGuiToProductQueryContainerBridge(
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
    protected function addPriceProductFacade(Container $container)
    {
        $container[static::FACADE_PRICE_PRODUCT] = function (Container $container) {
            return new ProductLabelGuiToPriceProductFacadeBridge(
                $container->getLocator()->priceProduct()->facade()
            );
        };

        return $container;
    }
}
