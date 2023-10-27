<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\ProductOfferServicePointAvailabilityCalculator;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\DefaultProductOfferServicePointAvailabilityCalculatorStrategy;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpander;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpanderInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface;

class ProductOfferServicePointAvailabilityCalculatorStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->getProductOfferServicePointAvailabilityStorageClient(),
            $this->getStoreClient(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Expander\ProductOfferServicePointAvailabilityRequestItemsExpanderInterface
     */
    public function createProductOfferServicePointAvailabilityRequestItemsExpander(): ProductOfferServicePointAvailabilityRequestItemsExpanderInterface
    {
        return new ProductOfferServicePointAvailabilityRequestItemsExpander();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface
     */
    public function createProductOfferServicePointAvailabilityCalculator(): ProductOfferServicePointAvailabilityCalculatorInterface
    {
        return new ProductOfferServicePointAvailabilityCalculator(
            $this->createProductOfferServicePointAvailabilityReader(),
            $this->createProductOfferServicePointAvailabilityRequestItemsExpander(),
            $this->createDefaultProductOfferServicePointAvailabilityCalculatorStrategy(),
            $this->getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy\ProductOfferServicePointAvailabilityCalculatorStrategyInterface
     */
    public function createDefaultProductOfferServicePointAvailabilityCalculatorStrategy(): ProductOfferServicePointAvailabilityCalculatorStrategyInterface
    {
        return new DefaultProductOfferServicePointAvailabilityCalculatorStrategy();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface
     */
    public function getProductOfferServicePointAvailabilityStorageClient(): ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorStorageDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorStorageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityCalculatorStrategyPluginInterface>
     */
    public function getProductOfferServicePointAvailabilityCalculatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityCalculatorStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR_STRATEGY);
    }
}
