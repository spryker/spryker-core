<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductReviewsRestApi\Processor\RestResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\ProductReviewTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface ProductReviewRestResponseBuilderInterface
{
    /**
     * @param int $itemsPerPage
     * @param int $pageLimit
     * @param \Generated\Shared\Transfer\ProductReviewTransfer[] $productReviewTransfers
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductReviewRestResponse(int $itemsPerPage, int $pageLimit, array $productReviewTransfers): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ProductReviewTransfer $productReviewTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createProductReviewRestResource(ProductReviewTransfer $productReviewTransfer): RestResourceInterface;

    /**
     * @param array $productReviewsData
     * @param int[] $productAbstractIds
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function prepareRestResourceCollection(array $productReviewsData, array $productAbstractIds): array;

    /**
     * @param int $totalItems
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestResponse(int $totalItems = 0, int $limit = 0): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createRestUserMissingErrorResponse(): RestResponseInterface;

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
