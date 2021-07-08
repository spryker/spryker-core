<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Relationship;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class CustomerByQuoteRequestResourceRelationshipExpander extends AbstractCustomerResourceRelationshipExpander
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function findCustomerTransferInPayload(RestResourceInterface $restResource): ?CustomerTransfer
    {
        /** @var \Generated\Shared\Transfer\QuoteRequestTransfer|null $payload */
        $payload = $restResource->getPayload();
        if (!$payload || !($payload instanceof QuoteRequestTransfer) || !$payload->getCompanyUser()->getCustomer()) {
            return null;
        }

        return $payload->getCompanyUser()->getCustomer();
    }
}
