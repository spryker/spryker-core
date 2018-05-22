<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

use Spryker\Client\Kernel\AbstractDependencyProvider;
use Spryker\Client\Kernel\Container;
use Spryker\Client\Price\Dependency\Client\PriceToQuoteClientBridge;

class PriceDependencyProvider extends AbstractDependencyProvider
{
    const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const PLUGINS_PRICE_MODE_POST_UPDATE = 'PLUGINS_PRICE_MODE_POST_UPDATE';

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    public function provideServiceLayerDependencies(Container $container)
    {
        $container = $this->addQuoteClient($container);
        $container = $this->addPriceModePostUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addQuoteClient(Container $container)
    {
        $container[static::CLIENT_QUOTE] = function (Container $container) {
            return new PriceToQuoteClientBridge($container->getLocator()->quote()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Client\Kernel\Container $container
     *
     * @return \Spryker\Client\Kernel\Container
     */
    protected function addPriceModePostUpdatePlugins(Container $container): Container
    {
        $container[static::PLUGINS_PRICE_MODE_POST_UPDATE] = function (Container $container) {
            return $this->getPriceModePostUpdatePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface[]
     */
    protected function getPriceModePostUpdatePlugins(): array
    {
        return [];
    }
}
