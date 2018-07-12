<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToCurrencyClientBridge;
use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceClientBridge;
use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductClientBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CatalogPriceProductConnectorDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_PRICE_PRODUCT = 'PRICE_PRODUCT_CLIENT';
    const CLIENT_PRICE = 'PRICE_CLIENT';
    const CLIENT_CURRENCY = 'CURRENCY_CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addPriceProductClient($container);
        $container = $this->addPriceClient($container);
        $container = $this->addCurrencyClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container)
    {
        $container[static::CLIENT_PRICE_PRODUCT] = function (Container $container) {
            return new CatalogPriceProductConnectorToPriceProductClientBridge($container->getLocator()->priceProduct()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceClient(Container $container)
    {
        $container[static::CLIENT_PRICE] = function (Container $container) {
            return new CatalogPriceProductConnectorToPriceClientBridge($container->getLocator()->price()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container)
    {
        $container[static::CLIENT_CURRENCY] = function (Container $container) {
            return new CatalogPriceProductConnectorToCurrencyClientBridge($container->getLocator()->currency()->client());
        };

        return $container;
    }
}
