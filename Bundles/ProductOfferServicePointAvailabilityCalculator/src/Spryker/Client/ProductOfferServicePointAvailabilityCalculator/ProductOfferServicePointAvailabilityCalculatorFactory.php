<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\ProductOfferServicePointAvailabilityCalculator;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\Strategy\DefaultProductOfferServicePointAvailabilityCalculatorStrategy;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Reader\ProductOfferServicePointAvailabilityReaderInterface;

class ProductOfferServicePointAvailabilityCalculatorFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->getProductOfferServicePointAvailabilityClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface
     */
    public function createProductOfferServicePointAvailabilityCalculator(): ProductOfferServicePointAvailabilityCalculatorInterface
    {
        return new ProductOfferServicePointAvailabilityCalculator(
            $this->createProductOfferServicePointAvailabilityReader(),
            $this->createDefaultProductOfferServicePointAvailabilityCalculatorStrategy(),
            $this->getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface
     */
    public function createDefaultProductOfferServicePointAvailabilityCalculatorStrategy(): ProductOfferServicePointAvailabilityCalculatorStrategyInterface
    {
        return new DefaultProductOfferServicePointAvailabilityCalculatorStrategy();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface
     */
    public function getProductOfferServicePointAvailabilityClient(): ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY);
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface>
     */
    public function getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY);
    }
}
