<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client\ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientInterface;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Builder\ProductOfferServicePointAvailabilityResponseBuilder;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Builder\ProductOfferServicePointAvailabilityResponseBuilderInterface;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapper;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapperInterface;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Reader\ProductOfferServicePointAvailabilityReader;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Reader\ProductOfferServicePointAvailabilityReaderInterface;

/**
 * @method \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig getConfig()
 */
class ProductOfferServicePointAvailabilitiesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Builder\ProductOfferServicePointAvailabilityResponseBuilderInterface
     */
    public function createProductOfferServicePointAvailabilityResponseBuilder(): ProductOfferServicePointAvailabilityResponseBuilderInterface
    {
        return new ProductOfferServicePointAvailabilityResponseBuilder(
            $this->getResourceBuilder(),
            $this->createProductOfferServicePointAvailabilityMapper(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper\ProductOfferServicePointAvailabilityMapperInterface
     */
    public function createProductOfferServicePointAvailabilityMapper(): ProductOfferServicePointAvailabilityMapperInterface
    {
        return new ProductOfferServicePointAvailabilityMapper();
    }

    /**
     * @return \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Reader\ProductOfferServicePointAvailabilityReaderInterface
     */
    public function createProductOfferServicePointAvailabilityReader(): ProductOfferServicePointAvailabilityReaderInterface
    {
        return new ProductOfferServicePointAvailabilityReader(
            $this->createProductOfferServicePointAvailabilityMapper(),
            $this->getProductOfferServicePointAvailabilityCalculatorClient(),
            $this->createProductOfferServicePointAvailabilityResponseBuilder(),
        );
    }

    /**
     * @return \Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client\ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientInterface
     */
    public function getProductOfferServicePointAvailabilityCalculatorClient(): ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientInterface
    {
        return $this->getProvidedDependency(ProductOfferServicePointAvailabilitiesRestApiDependencyProvider::CLIENT_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_CALCULATOR);
    }
}
