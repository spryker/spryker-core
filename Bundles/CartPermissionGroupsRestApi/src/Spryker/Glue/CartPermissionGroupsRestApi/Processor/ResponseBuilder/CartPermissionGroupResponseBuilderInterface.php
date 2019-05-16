<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder;

use ArrayObject;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CartPermissionGroupResponseBuilderInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createEmptyCartPermissionGroupsResponse(): RestResponseInterface;

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\QuotePermissionGroupTransfer[] $quotePermissionGroups
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupsCollectionResponse(ArrayObject $quotePermissionGroups): RestResponseInterface;

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupsResponse(QuotePermissionGroupTransfer $quotePermissionGroupTransfer): RestResponseInterface;

    /**
     * @param string $cartPermissionGroupUuid
     * @param \Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer $restCartPermissionGroupsAttributesTransfer
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null $quotePermissionGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCartPermissionGroupsResource(
        string $cartPermissionGroupUuid,
        RestCartPermissionGroupsAttributesTransfer $restCartPermissionGroupsAttributesTransfer,
        ?QuotePermissionGroupTransfer $quotePermissionGroupTransfer = null
    ): RestResourceInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createCartPermissionGroupNotFoundErrorResponse(): RestResponseInterface;
}
