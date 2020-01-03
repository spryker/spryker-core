<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToPriceProductAdapter;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToMessengerFacadeBridge;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceBridge;

/**
 * @method \Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig getConfig()
 */
class PriceCartConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_PRICE_PRODUCT = 'price product facade';
    public const FACADE_PRICE = 'price facade';
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_CURRENCY = 'FACADE_CURRENCY';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addPriceProductFacade($container);
        $container = $this->addPriceFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addCurrencyFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container)
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return new PriceCartConnectorToPriceProductAdapter($container->getLocator()->priceProduct()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceFacade(Container $container)
    {
        $container->set(static::FACADE_PRICE, function (Container $container) {
            return new PriceCartToPriceBridge($container->getLocator()->price()->facade());
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
            return new PriceCartToMessengerFacadeBridge($container->getLocator()->messenger()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return new PriceCartConnectorToCurrencyFacadeBridge($container->getLocator()->currency()->facade());
        });

        return $container;
    }
}
