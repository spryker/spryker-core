<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductBridge;

class PriceCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_PRODUCT = 'price product facade';
    public const FACADE_PRICE = 'price facade';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addPriceProductFacade($container);
        $container = $this->addPriceFacade($container);

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
            return new PriceCartToPriceProductBridge($container->getLocator()->priceProduct()->facade());
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
            return new PriceCartToPriceBridge($container->getLocator()->price()->facade());
        };
        return $container;
    }
}
