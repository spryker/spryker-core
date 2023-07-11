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
     * @param string $productAbstractSku
     * @param int $httpStatusCode
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewRestResponse(
        ProductReviewTransfer $productReviewTransfer,
        string $productAbstractSku,
        int $httpStatusCode
    ): RestResponseInterface;

    /**
     * @param array<\Generated\Shared\Transfer\ProductReviewTransfer> $productReviewTransfers
     * @param string $productAbstractSku
     * @param int $totalItems
     * @param int $pageLimit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsCollectionRestResponse(
        array $productReviewTransfers,
        string $productAbstractSku,
        int $totalItems = 0,
        int $pageLimit = 0
    ): RestResponseInterface;

    /**
     * @param array<array<\Generated\Shared\Transfer\ProductReviewTransfer>> $indexedProductReviewTransfers
     * @param array<int, array<string, mixed>> $productAbstractDataCollection
     *
     * @return array<array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>>
     */
    public function createRestResourceCollection(array $indexedProductReviewTransfers, array $productAbstractDataCollection): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractSkuMissingErrorResponse(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAbstractNotFoundErrorResponse(): RestResponseInterface;

    /**
     * @param string $idProductReview
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewNotFoundErrorResponse(string $idProductReview): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createNotImplementedErrorResponse(): RestResponseInterface;

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductReviewErrorTransfer> $productReviewErrorTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewsRestResponseWithErrors(ArrayObject $productReviewErrorTransfers): RestResponseInterface;
}
