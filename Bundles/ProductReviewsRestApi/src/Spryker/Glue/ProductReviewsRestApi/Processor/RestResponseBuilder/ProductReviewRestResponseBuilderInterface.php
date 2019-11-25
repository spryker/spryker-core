<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductReviewRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewRestResponse(ProductReviewTransfer $productReviewTransfer): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     * @param int $totalItems
     * @param int $pageLimit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsCollectionRestResponse(
        array $productReviewTransfers,
        int $totalItems = 0,
        int $pageLimit = 0
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[][] $indexedProductReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[][]
     */
    public function createRestResourceCollection(array $indexedProductReviewTransfers): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuMissingErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNotImplementedErrorResponse(): RestResponseInterface;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ProductReviewErrorTransfer[] $productReviewErrorTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsRestResponseWithErrors(ArrayObject $productReviewErrorTransfers): RestResponseInterface;
}
