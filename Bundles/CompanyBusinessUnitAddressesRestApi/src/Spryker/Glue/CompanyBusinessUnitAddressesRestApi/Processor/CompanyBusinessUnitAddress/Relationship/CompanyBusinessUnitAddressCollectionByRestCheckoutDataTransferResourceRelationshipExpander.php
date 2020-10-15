<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Processor\CompanyBusinessUnitAddress\Relationship;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\RestCheckoutDataTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CompanyBusinessUnitAddressCollectionByRestCheckoutDataTransferResourceRelationshipExpander extends AbstractCompanyBusinessUnitAddressCollectionResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer|null
     */
    protected function findAddressCollectionTransferInPayload(RestResourceInterface $resource): ?CompanyUnitAddressCollectionTransfer
    {
        /**
         * @var \Generated\Shared\Transfer\RestCheckoutDataTransfer|null $payload
         */
        $payload = $resource->getPayload();

        if (
            !$payload
            || !($payload instanceof RestCheckoutDataTransfer)
            || !$this->hasCompanyUnitAddressCollection($payload)
        ) {
            return null;
        }

        return $payload->getCompanyBusinessUnitAddresses();
    }

    /**
     * @param \Generated\Shared\Transfer\RestCheckoutDataTransfer $restCheckoutDataTransfer
     *
     * @return bool
     */
    protected function hasCompanyUnitAddressCollection(RestCheckoutDataTransfer $restCheckoutDataTransfer): bool
    {
        return $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses()
            && $restCheckoutDataTransfer->getCompanyBusinessUnitAddresses()->getCompanyUnitAddresses()->count() > 0;
    }
}
