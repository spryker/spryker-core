<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ContentProductAbstractListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ContentProductAbstractListRestResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentItemIdNotSpecifiedError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentItemtNotFoundError(): RestResponseInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addContentTypeInvalidError(): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createContentProductAbstractListsRestResponse(
        ContentProductAbstractListTypeTransfer $contentProductAbstractListTypeTransfer,
        RestRequestInterface $restRequest
    ): RestResponseInterface;

    /**
     * @phpstan-param array<string, \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer> $contentProductAbstractListTypeTransfers
     *
     * @phpstan-return array<string, \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
     *
     * @param \Generated\Shared\Transfer\ContentProductAbstractListTypeTransfer[] $contentProductAbstractListTypeTransfers
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $storeName
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function createContentProductAbstractListsRestResources(
        array $contentProductAbstractListTypeTransfers,
        RestRequestInterface $restRequest,
        string $storeName
    ): array;
}
