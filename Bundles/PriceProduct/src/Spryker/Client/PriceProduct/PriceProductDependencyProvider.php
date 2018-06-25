<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientBridge;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientBridge;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientBridge;

class PriceProductDependencyProvider extends AbstractDependencyProvider
{
    public const CLIENT_PRICE = 'CLIENT_PRICE';
    public const CLIENT_CURRENCY = 'CLIENT_CURRENCY';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    public const SERVICE_PRICE_PRODUCT = 'SERVICE_PRICE_PRODUCT';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container): Container
    {
        $container = $this->addPriceProductClient($container);
        $container = $this->addCurrencyClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addPriceProductService($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductClient(Container $container): Container
    {
        $container[static::CLIENT_PRICE] = function (Container $container) {
            return new PriceProductToPriceClientBridge($container->getLocator()->price()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addCurrencyClient(Container $container): Container
    {
        $container[static::CLIENT_CURRENCY] = function (Container $container) {
            return new PriceProductToCurrencyClientBridge($container->getLocator()->currency()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceProductService(Container $container): Container
    {
        $container[static::SERVICE_PRICE_PRODUCT] = function (Container $container) {
            return $container->getLocator()->priceProduct()->service();
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient($container): Container
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new PriceProductToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }
}
