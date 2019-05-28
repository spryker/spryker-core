<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProduct;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\PriceProduct\DataReader\PriceEnvironmentReader;
use Spryker\Client\PriceProduct\DataReader\PriceEnvironmentReaderInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface;
use Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface;
use Spryker\Client\PriceProduct\Dependency\Service\PriceProductToUtilPriceServiceInterface;
use Spryker\Client\PriceProduct\ProductPriceResolver\ProductPriceResolver;
use Spryker\Service\PriceProduct\PriceProductServiceInterface;

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
            $this->getConfig(),
            $this->getPriceProductService(),
            $this->createPriceEnvironmentReader(),
            $this->getUtilPriceService()
        );
    }

    /**
     * @return \Spryker\Client\PriceProduct\DataReader\PriceEnvironmentReaderInterface
     */
    public function createPriceEnvironmentReader(): PriceEnvironmentReaderInterface
    {
        return new PriceEnvironmentReader(
            $this->getPriceClient(),
            $this->getCurrencyClient(),
            $this->getQuoteClient()
        );
    }

    /**
     * @return \Spryker\Service\PriceProduct\PriceProductServiceInterface
     */
    public function getPriceProductService(): PriceProductServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::SERVICE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToPriceClientInterface
     */
    public function getPriceClient(): PriceProductToPriceClientInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::CLIENT_PRICE);
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToCurrencyClientInterface
     */
    public function getCurrencyClient(): PriceProductToCurrencyClientInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::CLIENT_CURRENCY);
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Client\PriceProductToQuoteClientInterface
     */
    public function getQuoteClient(): PriceProductToQuoteClientInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \Spryker\Client\PriceProduct\PriceProductConfig
     */
    public function getModuleConfig()
    {
        /** @var \Spryker\Client\PriceProduct\PriceProductConfig $config */
        $config = parent::getConfig();

        return $config;
    }

    /**
     * @return \Spryker\Client\PriceProduct\Dependency\Service\PriceProductToUtilPriceServiceInterface
     */
    public function getUtilPriceService(): PriceProductToUtilPriceServiceInterface
    {
        return $this->getProvidedDependency(PriceProductDependencyProvider::SERVICE_UTIL_PRICE);
    }
}
