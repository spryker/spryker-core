<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Expander\ProductOfferAvailabilityExpander;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Expander\ProductOfferAvailabilityExpanderInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapper;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapperInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReader;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReaderInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilder;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilderInterface;

class ProductOfferAvailabilitiesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReaderInterface
     */
    public function createProductOfferAvailabilityReader(): ProductOfferAvailabilityReaderInterface
    {
        return new ProductOfferAvailabilityReader(
            $this->getProductOfferAvailabilityStorageClient(),
            $this->getStoreClient(),
            $this->createProductOfferAvailabilityRestResponseBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Mapper\ProductOfferAvailabilityMapperInterface
     */
    public function createProductOfferAvailabilityMapper(): ProductOfferAvailabilityMapperInterface
    {
        return new ProductOfferAvailabilityMapper();
    }

    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\RestResponseBuilder\ProductOfferAvailabilityRestResponseBuilderInterface
     */
    public function createProductOfferAvailabilityRestResponseBuilder(): ProductOfferAvailabilityRestResponseBuilderInterface
    {
        return new ProductOfferAvailabilityRestResponseBuilder(
            $this->getResourceBuilder(),
            $this->createProductOfferAvailabilityMapper()
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface
     */
    public function getProductOfferAvailabilityStorageClient(): ProductOfferAvailabilitiesRestApiToProductOfferAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilitiesRestApiDependencyProvider::CLIENT_PRODUCT_OFFER_AVAILABILITY_STORAGE);
    }

    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Dependency\Client\ProductOfferAvailabilitiesRestApiToStoreClientInterface
     */
    public function getStoreClient(): ProductOfferAvailabilitiesRestApiToStoreClientInterface
    {
        return $this->getProvidedDependency(ProductOfferAvailabilitiesRestApiDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Expander\ProductOfferAvailabilityExpanderInterface
     */
    public function createProductOfferAvailabilityExpander(): ProductOfferAvailabilityExpanderInterface
    {
        return new ProductOfferAvailabilityExpander(
            $this->createProductOfferAvailabilityReader()
        );
    }
}
