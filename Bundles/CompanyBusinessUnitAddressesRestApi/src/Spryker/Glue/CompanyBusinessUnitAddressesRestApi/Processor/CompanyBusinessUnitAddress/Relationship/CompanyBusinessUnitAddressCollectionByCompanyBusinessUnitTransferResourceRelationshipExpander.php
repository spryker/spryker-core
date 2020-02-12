<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Relationship;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyBusinessUnitAddressCollectionByCompanyBusinessUnitTransferResourceRelationshipExpander extends AbstractCompanyBusinessUnitAddressCollectionResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer|null
     */
    protected function findAddressCollectionTransferInPayload(RestResourceInterface $resource): ?CompanyUnitAddressCollectionTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\CompanyBusinessUnitTransfer|null $payload
         */
        $payload = $resource->getPayload();

        if (
            !$payload
            || !($payload instanceof CompanyBusinessUnitTransfer)
            || !$this->hasAddressCollection($payload)
        ) {
            return null;
        }

        return $payload->getAddressCollection();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    protected function hasAddressCollection(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        return $companyBusinessUnitTransfer->getAddressCollection()
            && $companyBusinessUnitTransfer->getAddressCollection()->getCompanyUnitAddresses()->count() > 0;
    }
}
