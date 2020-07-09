<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferPricesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReaderInterface;
use Spryker\Glue\ProductOfferPricesRestApi\ProductOfferPricesRestApiConfig;

class ProductOfferPriceExpander implements ProductOfferPriceExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReaderInterface
     */
    protected $productOfferPriceReader;

    /**
     * @param \Spryker\Glue\ProductOfferPricesRestApi\Processor\Reader\ProductOfferPriceReaderInterface $productOfferPriceReader
     */
    public function __construct(ProductOfferPriceReaderInterface $productOfferPriceReader)
    {
        $this->productOfferPriceReader = $productOfferPriceReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addProductOfferPriceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $productOfferReferences = $this->getProductOfferReferences($resources);

        $productOfferPriceRestResources = $this->productOfferPriceReader->getProductOfferPriceRestResources(
            $productOfferReferences,
            $restRequest->getMetadata()->getLocale()
        );

        foreach ($resources as $resource) {
            if (
                $resource->getType() !== ProductOfferPricesRestApiConfig::RESOURCE_PRODUCT_OFFERS
                || !isset($productOfferPriceRestResources[$resource->getId()])
            ) {
                continue;
            }

            $resource->addRelationship($productOfferPriceRestResources[$resource->getId()]);
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
            if ($resource->getType() !== ProductOfferPricesRestApiConfig::RESOURCE_PRODUCT_OFFERS) {
                continue;
            }

            $productOfferReferences[] = $resource->getId();
        }

        return $productOfferReferences;
    }
}
