<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CustomerByCompanyUserResourceRelationshipExpander extends AbstractCustomerResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerTransferInPayload(RestResourceInterface $restResource): ?CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\CompanyUserTransfer|null $payload */
        $payload = $restResource->getPayload();
        if (!$payload || !($payload instanceof CompanyUserTransfer) || !$payload->getCustomer()) {
            return null;
        }

        return $payload->getCustomer();
    }
}
