<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Builder;

use Generated\Shared\Transfer\ReturnCollectionTransfer;
use Generated\Shared\Transfer\ReturnFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface RestReturnResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnFilterTransfer $returnFilterTransfer
     * @param \Generated\Shared\Transfer\ReturnCollectionTransfer $returnCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnListRestResponse(
        ReturnFilterTransfer $returnFilterTransfer,
        ReturnCollectionTransfer $returnCollectionTransfer
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createReturnRestResponse(ReturnTransfer $returnTransfer): RestResponseInterface;

    /**
     * @param string $message
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorRestResponse(string $message): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ReturnResponseTransfer $returnResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createErrorRestResponseFromReturnResponse(
        ReturnResponseTransfer $returnResponseTransfer
    ): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createReturnItemRestResourcesFromReturnTransfer(ReturnTransfer $returnTransfer): array;
}
