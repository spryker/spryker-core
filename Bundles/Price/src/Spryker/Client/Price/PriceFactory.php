<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Price;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Price\PriceModeCache\PriceModeCache;
use Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface;
use Spryker\Client\Price\PriceModeResolver\PriceModeResolver;
use Spryker\Client\Price\PriceModeSwitcher\PriceModeSwitcher;
use Spryker\Client\Price\PriceModeSwitcher\PriceModeSwitcherInterface;

/**
 * @method \Spryker\Client\Price\PriceConfig getConfig()
 */
class PriceFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Price\PriceModeResolver\PriceModeResolverInterface
     */
    public function createPriceModeResolver()
    {
        return new PriceModeResolver($this->getQuoteClient(), $this->getConfig(), $this->createPriceModeCache());
    }

    /**
     * @return \Spryker\Client\Price\PriceModeSwitcher\PriceModeSwitcherInterface
     */
    public function createPriceModeSwitcher(): PriceModeSwitcherInterface
    {
        return new PriceModeSwitcher(
            $this->getQuoteClient(),
            $this->getConfig(),
            $this->getPriceModePostUpdatePlugins(),
            $this->createPriceModeCache()
        );
    }

    /**
     * @return \Spryker\Client\Price\PriceModeCache\PriceModeCacheInterface
     */
    public function createPriceModeCache(): PriceModeCacheInterface
    {
        return new PriceModeCache();
    }

    /**
     * @return \Spryker\Client\Price\Dependency\Client\PriceToQuoteClientInterface
     */
    protected function getQuoteClient()
    {
        return $this->getProvidedDependency(PriceDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\Price\PriceConfig
     */
    public function getModuleConfig()
    {
        /** @var \Spryker\Client\Price\PriceConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\PriceExtension\Dependency\Plugin\PriceModePostUpdatePluginInterface[]
     */
    protected function getPriceModePostUpdatePlugins(): array
    {
        return $this->getProvidedDependency(PriceDependencyProvider::PLUGINS_PRICE_MODE_POST_UPDATE);
    }
}
