<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeBridge;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeBridge;
use Spryker\Service\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeBridge;
use Spryker\Service\PriceProduct\Plugin\DefaultPriceDimensionDecisionPlugin;

class PriceProductDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';
    public const FACADE_PRICE = 'FACADE_PRICE';
    public const FACADE_STORE = 'FACADE_STORE';

    public const PLUGIN_PRICE_PRODUCT_DECISION = 'PLUGIN_PRICE_PRODUCT_DECISION';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = $this->addCurrencyFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addPriceProductDecisionPlugins($container);
        $container = $this->addStoreFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addPriceProductDecisionPlugins(Container $container): Container
    {
        $container[static::PLUGIN_PRICE_PRODUCT_DECISION] = function () {
            return $this->getPriceProductDecisionPlugins();
        };

        return $container;
    }

    /**
     * The plugins in this stack will filter data returned by price query.
     *
     * @return \Spryker\Service\PriceProduct\Dependency\Plugin\PriceProductDecisionPluginInterface[]
     */
    protected function getPriceProductDecisionPlugins(): array
    {
        return [
            new DefaultPriceDimensionDecisionPlugin(),
        ];
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container[static::FACADE_CURRENCY] = function (Container $container) {
            return new PriceProductToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addPriceFacade(Container $container): Container
    {
        $container[static::FACADE_PRICE] = function (Container $container) {
            return new PriceProductToPriceFacadeBridge($container->getLocator()->price()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container[static::FACADE_STORE] = function (Container $container) {
            return new PriceProductToStoreFacadeBridge($container->getLocator()->store()->facade());
        };

        return $container;
    }
}
