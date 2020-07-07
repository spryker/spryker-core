<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\MerchantProductOffersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductOfferRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferEmptyRestResponse(): RestResponseInterface;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $productOfferRestResources
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferCollectionRestResponse(
        array $productOfferRestResources
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageTransfer $productOfferStorageTransfer
     * @param string $defaultMerchantProductOfferReference
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferRestResponse(
        ProductOfferStorageTransfer $productOfferStorageTransfer,
        string $defaultMerchantProductOfferReference
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function createProductOfferRestResources(ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductConcreteSkuNotSpecifiedErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferIdNotSpecifiedErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductOfferNotFoundErrorResponse(): RestResponseInterface;
}
