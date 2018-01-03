<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToCurrencyFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToPriceFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToProductFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToStoreFacadeBridge;
use Spryker\Zed\PriceProduct\Dependency\Facade\PriceProductToTouchFacadeBridge;

class PriceProductDependencyProvider extends AbstractBundleDependencyProvider
{
    const FACADE_TOUCH = 'facade touch';
    const FACADE_PRODUCT = 'product facade';
    const FACADE_CURRENCY = 'currency facade';
    const FACADE_PRICE = 'price facade';
    const FACADE_STORE = 'store facade';

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
}
