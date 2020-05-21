<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\Expander;

use Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig;
use Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var \Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface
     */
    protected $productOfferReader;

    /**
     * @param \Spryker\Glue\MerchantProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface $productOfferReader
     */
    public function __construct(ProductOfferReaderInterface $productOfferReader)
    {
        $this->productOfferReader = $productOfferReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return void
     */
    public function expandProductConcretesWithProductOffers(array $resources): void
    {
        $productConcreteSku = $this->getProductConcreteSkus($resources);

        $productOffersRestResources = $this->productOfferReader->getProductOfferResources($productConcreteSku);

        foreach ($resources as $resource) {
            if ($resource->getType() !== MerchantProductOffersRestApiConfig::RESOURCE_CONCRETE_PRODUCTS) {
                continue;
            }

            if (!isset($productOffersRestResources[$resource->getId()])) {
                continue;
            }

            foreach ($productOffersRestResources[$resource->getId()] as $productConcreteProductOffersRestResources) {
                $resource->addRelationship($productConcreteProductOffersRestResources);
            }
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     *
     * @return array<string>
     */
    protected function getProductConcreteSkus(array $resources): array
    {
        $productConcreteSku = [];

        foreach ($resources as $resource) {
            if ($resource->getType() !== MerchantProductOffersRestApiConfig::RESOURCE_CONCRETE_PRODUCTS) {
                continue;
            }

            $productConcreteSku[] = $resource->getId();
        }

        return $productConcreteSku;
    }
}
