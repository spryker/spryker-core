<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProduct\Communication\Plugin\DefaultPriceQueryCriteriaPlugin;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeBridge;

class PriceProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_TOUCH = 'facade touch';
    public const FACADE_PRODUCT = 'product facade';
    public const FACADE_CURRENCY = 'currency facade';
    public const FACADE_PRICE = 'price facade';
    public const FACADE_STORE = 'store facade';

    public const SERVICE_PRICE_PRODUCT = 'SERVICE_PRICE_PRODUCT';

    public const PLUGIN_PRICE_DIMENSION_QUERY_CRITERIA = 'PLUGIN_PRICE_DIMENSION_QUERY_CRITERIA';
    public const PLUGIN_PRICE_DIMENSION_ABSTRACT_SAVER = 'PLUGIN_PRICE_DIMENSION_ABSTRACT_SAVER';
    public const PLUGIN_PRICE_DIMENSION_CONCRETE_SAVER = 'PLUGIN_PRICE_DIMENSION_CONCRETE_SAVER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addTouchFacade($container);
        $container = $this->addProductFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addPriceProductService($container);
        $container = $this->addPriceDimensionAbstractSaverPlugins($container);
        $container = $this->addPriceDimensionConcreteSaverPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addPriceDimensionQueryCriteriaPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTouchFacade(Container $container)
    {
        $container[static::FACADE_TOUCH] = function (Container $container) {
            return new PriceProductToTouchFacadeBridge($container->getLocator()->touch()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductFacade(Container $container)
    {
        $container[static::FACADE_PRODUCT] = function (Container $container) {
            return new PriceProductToProductFacadeBridge($container->getLocator()->product()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container)
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new PriceProductToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container[static::FACADE_PRICE] = function (Container $container) {
            return new PriceProductToPriceFacadeBridge($container->getLocator()->price()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container)
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new PriceProductToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceDimensionQueryCriteriaPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRICE_DIMENSION_QUERY_CRITERIA] = function (Container $container) {
            return $this->getPriceDimensionQueryCriteriaPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceDimensionAbstractSaverPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRICE_DIMENSION_ABSTRACT_SAVER] = function (Container $container) {
            return $this->getPriceDimensionAbstractSaverPlugins();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceDimensionConcreteSaverPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRICE_DIMENSION_CONCRETE_SAVER] = function (Container $container) {
            return $this->getPriceDimensionConcreteSaverPlugins();
        };

        return $container;
    }

    /**
     * The plugins in this stack will provide additional criteria to main price product query.
     *
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface[]
     */
    protected function getPriceDimensionQueryCriteriaPlugins(): array
    {
        return [
            new DefaultPriceQueryCriteriaPlugin(),
        ];
    }

    /**
     * The plugins are executed when saving abstract product price
     *
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionAbstractSaverPluginInterface[]
     */
    protected function getPriceDimensionAbstractSaverPlugins(): array
    {
        return [];
    }

    /**
     * The plugins are executed when saving concrete product price
     *
     * @return \Spryker\Zed\PriceProduct\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface[]
     */
    protected function getPriceDimensionConcreteSaverPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductService(Container $container): Container
    {
        $container[static::SERVICE_PRICE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->priceProduct()->service();
        };

        return $container;
    }
}
