<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CartPermissionGroupByShareDetailResourceRelationshipExpander extends AbstractCartPermissionGroupResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    protected function findQuotePermissionGroupTransferInPayload(RestResourceInterface $resource): ?QuotePermissionGroupTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\ShareDetailTransfer|null $payload
         */
        $payload = $resource->getPayload();
        if (!$payload || !($payload instanceof ShareDetailTransfer)) {
            return null;
        }

        return $payload->getQuotePermissionGroup();
    }
}
