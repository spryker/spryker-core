<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolver;

/**
 * @method \Spryker\Client\PriceProduct\PriceProductConfig getConfig()
 */
class PriceProductFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolverInterface
     */
    public function createProductPriceResolver()
    {
        return new ProductPriceResolver(
            $this->getPriceClient(),
            $this->getCurrencyClient(),
            $this->getConfig(),
            $this->getPriceDimensionPlugins()
        );
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface
     */
    protected function getPriceClient()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    protected function getCurrencyClient()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Plugin\PriceDimensionPluginInterface[]
     */
    protected function getPriceDimensionPlugins()
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::PLUGIN_PRICE_DIMENSION);
    }

    /**
     * @return \Spryker\Client\PriceProduct\PriceProductConfig|\Spryker\Client\Kernel\AbstractBundleConfig
     */
    public function getModuleConfig()
    {
        return parent::getConfig();
    }
}
