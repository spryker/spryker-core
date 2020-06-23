<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Expander;

use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReaderInterface;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\ProductOfferAvailabilitiesRestApiConfig;

class ProductOfferAvailabilityExpander implements ProductOfferAvailabilityExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReaderInterface
     */
    protected $productOfferAvailabilityReader;

    /**
     * @param \Spryker\Glue\ProductOfferAvailabilitiesRestApi\Processor\Reader\ProductOfferAvailabilityReaderInterface $productOfferAvailabilityReader
     */
    public function __construct(ProductOfferAvailabilityReaderInterface $productOfferAvailabilityReader)
    {
        $this->productOfferAvailabilityReader = $productOfferAvailabilityReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return void
     */
    public function addProductOfferAvailabilitiesRelationships(array $resources): void
    {
        $productOfferReferences = $this->getProductOfferReferences($resources);

        $productOfferAvailabilityRestResources = $this->productOfferAvailabilityReader
            ->getProductOfferAvailabilityRestResources($productOfferReferences);

        foreach ($resources as $resource) {
            if (
                $resource->getType() !== ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFERS
                || !isset($productOfferAvailabilityRestResources[$resource->getId()])
            ) {
                continue;
            }

            $resource->addRelationship($productOfferAvailabilityRestResources[$resource->getId()]);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return string[]
     */
    protected function getProductOfferReferences(array $resources): array
    {
        $productOfferReferences = [];
        foreach ($resources as $resource) {
            if ($resource->getType() !== ProductOfferAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFERS) {
                continue;
            }

            $productOfferReferences[] = $resource->getId();
        }

        return $productOfferReferences;
    }
}
