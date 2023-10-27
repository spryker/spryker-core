<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractor;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractor;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilter;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilterInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ServiceStorageFilter;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ServiceStorageFilterInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferStorageReader;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferStorageReaderInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizer;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface;

class ProductOfferServicePointAvailabilityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->getProductOfferAvailabilityStorageClient(),
            $this->createProductOfferStorageReader(),
            $this->createProductOfferStorageExtractor(),
            $this->createProductOfferServicePointAvailabilitySanitizer(),
            $this->getProductOfferServicePointAvailabilityFilterPlugins(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface
     */
    public function createProductOfferServicePointAvailabilitySanitizer(): ProductOfferServicePointAvailabilitySanitizerInterface
    {
        return new ProductOfferServicePointAvailabilitySanitizer();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface
     */
    public function createProductOfferServicePointAvailabilityRequestItemExtractor(): ProductOfferServicePointAvailabilityRequestItemExtractorInterface
    {
        return new ProductOfferServicePointAvailabilityRequestItemExtractor();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferStorageExtractorInterface
     */
    public function createProductOfferStorageExtractor(): ProductOfferStorageExtractorInterface
    {
        return new ProductOfferStorageExtractor();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilterInterface
     */
    public function createProductOfferStorageFilter(): ProductOfferStorageFilterInterface
    {
        return new ProductOfferStorageFilter(
            $this->createServiceStorageFilter(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ServiceStorageFilterInterface
     */
    public function createServiceStorageFilter(): ServiceStorageFilterInterface
    {
        return new ServiceStorageFilter();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader\ProductOfferStorageReaderInterface
     */
    public function createProductOfferStorageReader(): ProductOfferStorageReaderInterface
    {
        return new ProductOfferStorageReader(
            $this->getProductOfferStorageClient(),
            $this->createProductOfferServicePointAvailabilityRequestItemExtractor(),
            $this->createProductOfferStorageFilter(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface
     */
    public function getProductOfferAvailabilityStorageClient(): ProductOfferServicePointAvailabilityStorageToProductOfferAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityStorageDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityStorageDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityStorageExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface>
     */
    public function getProductOfferServicePointAvailabilityFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityStorageDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_FILTER);
    }
}
