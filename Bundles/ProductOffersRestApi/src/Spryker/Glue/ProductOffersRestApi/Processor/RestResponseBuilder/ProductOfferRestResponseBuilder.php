<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOffersRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\RestProductOffersAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductOffersRestApi\ProductOffersRestApiConfig;

class ProductOfferRestResponseBuilder implements ProductOfferRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \ArrayObject $productOfferStorageTransfers
     *
     * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function createProductOfferRestResourcesByReference(
        ArrayObject $productOfferStorageTransfers
    ): array {
        $productOffersRestResources = [];

        foreach ($productOfferStorageTransfers as $productOfferStorageTransfer) {
            $productOffersRestResources[$productOfferStorageTransfer->getProductOfferReferenceOrFail()][] = $this->createProductOfferRestResource($productOfferStorageTransfer);
        }

        return $productOffersRestResources;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string|null $defaultProductOfferReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createProductOfferRestResource(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        ?string $defaultProductOfferReference = null
    ): RestResourceInterface {
        $restProductOffersAttributesTransfer = (new RestProductOffersAttributesTransfer())
            ->fromArray($productOfferStorageTransfer->toArray(), true);

        if ($defaultProductOfferReference) {
            $restProductOffersAttributesTransfer->setIsDefault($defaultProductOfferReference === $productOfferStorageTransfer->getProductOfferReferenceOrFail());
        }

        return $this->restResourceBuilder->createRestResource(
            ProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            $productOfferStorageTransfer->getProductOfferReferenceOrFail(),
            $restProductOffersAttributesTransfer,
        );
    }
}
