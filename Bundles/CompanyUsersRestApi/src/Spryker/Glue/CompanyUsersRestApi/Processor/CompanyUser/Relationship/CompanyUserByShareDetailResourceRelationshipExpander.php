<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Processor\CompanyUser\Relationship;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ShareDetailTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyUserByShareDetailResourceRelationshipExpander extends AbstractCompanyUserResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    protected function findCompanyUserTransferInPayload(RestResourceInterface $resource): ?CompanyUserTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\ShareDetailTransfer|null $payload
         */
        $payload = $resource->getPayload();
        if (!$payload || !($payload instanceof ShareDetailTransfer)) {
            return null;
        }

        return $payload->getCompanyUser();
    }
}
