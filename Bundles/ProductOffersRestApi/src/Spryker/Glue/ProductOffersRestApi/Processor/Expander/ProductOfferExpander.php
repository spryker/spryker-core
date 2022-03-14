<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Processor\Expander;

use Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var string
     */
    protected const RESOURCE_ATTRIBUTE_PRODUCT_OFFER_REFERENCE = 'productOfferReference';

    /**
     * @var \Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface
     */
    protected $productOfferReader;

    /**
     * @param \Spryker\Glue\ProductOffersRestApi\Processor\Reader\ProductOfferReaderInterface $productOfferReader
     */
    public function __construct(ProductOfferReaderInterface $productOfferReader)
    {
        $this->productOfferReader = $productOfferReader;
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     *
     * @return void
     */
    public function addProductOffersRelationshipsByReference(array $resources): void
    {
        $productOfferReferences = $this->getProductOfferReferences($resources);

        $productOffersRestResources = $this->productOfferReader->getProductOfferResourcesByProductOfferReferences($productOfferReferences);

        foreach ($resources as $resource) {
            $productOfferReference = null;

            $attributesTransfer = $resource->getAttributes();

            if ($attributesTransfer && $attributesTransfer->offsetExists(static::RESOURCE_ATTRIBUTE_PRODUCT_OFFER_REFERENCE)) {
                $productOfferReference = $attributesTransfer->offsetGet(static::RESOURCE_ATTRIBUTE_PRODUCT_OFFER_REFERENCE);
            }

            if (!$productOfferReference || !array_key_exists($productOfferReference, $productOffersRestResources)) {
                continue;
            }

            foreach ($productOffersRestResources[$productOfferReference] as $shoppingListItemsRestResources) {
                $resource->addRelationship($shoppingListItemsRestResources);
            }
        }
    }

    /**
     * @param array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface> $resources
     *
     * @return array<string>
     */
    protected function getProductOfferReferences(array $resources): array
    {
        $productOfferReferences = [];

        foreach ($resources as $resource) {
            $productOfferReference = null;
            $attributesTransfer = $resource->getAttributes();

            if ($attributesTransfer && $attributesTransfer->offsetExists(static::RESOURCE_ATTRIBUTE_PRODUCT_OFFER_REFERENCE)) {
                $productOfferReference = $attributesTransfer->offsetGet(static::RESOURCE_ATTRIBUTE_PRODUCT_OFFER_REFERENCE);
            }

            if ($productOfferReference) {
                $productOfferReferences[] = $productOfferReference;
            }
        }

        return $productOfferReferences;
    }
}
