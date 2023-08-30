<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailability;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductOfferServicePointAvailability\Dependency\Client\ProductOfferServicePointAvailabilityToProductOfferAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Dependency\Client\ProductOfferServicePointAvailabilityToProductOfferStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractor;
use Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferStorageExtractor;
use Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferStorageExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Filter\ProductOfferStorageFilter;
use Spryker\Client\ProductOfferServicePointAvailability\Filter\ProductOfferStorageFilterInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Filter\ServiceStorageFilter;
use Spryker\Client\ProductOfferServicePointAvailability\Filter\ServiceStorageFilterInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferServicePointAvailabilityReaderInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferStorageReader;
use Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferStorageReaderInterface;
use Spryker\Client\ProductOfferServicePointAvailability\Sanitizer\ProductOfferServicePointAvailabilitySanitizer;
use Spryker\Client\ProductOfferServicePointAvailability\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface;

class ProductOfferServicePointAvailabilityFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferServicePointAvailabilityReaderInterface
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
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Sanitizer\ProductOfferServicePointAvailabilitySanitizerInterface
     */
    public function createProductOfferServicePointAvailabilitySanitizer(): ProductOfferServicePointAvailabilitySanitizerInterface
    {
        return new ProductOfferServicePointAvailabilitySanitizer();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface
     */
    public function createProductOfferServicePointAvailabilityRequestItemExtractor(): ProductOfferServicePointAvailabilityRequestItemExtractorInterface
    {
        return new ProductOfferServicePointAvailabilityRequestItemExtractor();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Extractor\ProductOfferStorageExtractorInterface
     */
    public function createProductOfferStorageExtractor(): ProductOfferStorageExtractorInterface
    {
        return new ProductOfferStorageExtractor();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Filter\ProductOfferStorageFilterInterface
     */
    public function createProductOfferStorageFilter(): ProductOfferStorageFilterInterface
    {
        return new ProductOfferStorageFilter(
            $this->createServiceStorageFilter(),
        );
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Filter\ServiceStorageFilterInterface
     */
    public function createServiceStorageFilter(): ServiceStorageFilterInterface
    {
        return new ServiceStorageFilter();
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Reader\ProductOfferStorageReaderInterface
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
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Dependency\Client\ProductOfferServicePointAvailabilityToProductOfferAvailabilityStorageClientInterface
     */
    public function getProductOfferAvailabilityStorageClient(): ProductOfferServicePointAvailabilityToProductOfferAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductOfferServicePointAvailability\Dependency\Client\ProductOfferServicePointAvailabilityToProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): ProductOfferServicePointAvailabilityToProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return list<\Spryker\Client\ProductOfferServicePointAvailabilityExtension\Dependency\Plugin\ProductOfferServicePointAvailabilityFilterPluginInterface>
     */
    public function getProductOfferServicePointAvailabilityFilterPlugins(): array
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilityDependencyProvider::PLUGINS_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_FILTER);
    }
}
