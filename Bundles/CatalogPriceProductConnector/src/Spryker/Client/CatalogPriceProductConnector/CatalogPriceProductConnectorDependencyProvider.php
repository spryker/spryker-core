<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CatalogPriceProductConnector;

use Spryker\Client\CatalogPriceProductConnector\Dependency\CatalogPriceProductConnectorToPriceProductBridge;
use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;

class CatalogPriceProductConnectorDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_PRICE_PRODUCT = 'PRICE_PRODUCT_CLIENT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addPriceProductClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container)
    {
        $container[static::CLIENT_PRICE_PRODUCT] = function(Container $container) {
           return new CatalogPriceProductConnectorToPriceProductBridge($container->getLocator()->priceProduct()->client());
        };

        return $container;
    }
}
