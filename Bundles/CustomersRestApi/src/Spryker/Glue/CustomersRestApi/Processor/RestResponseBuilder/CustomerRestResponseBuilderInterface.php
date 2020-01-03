<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

interface CustomerRestResponseBuilderInterface
{
    /**
     * @param string $customerUuid
     * @param \Generated\Shared\Transfer\RestCustomersResponseAttributesTransfer $restCustomersResponseAttributesTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function createCustomerRestResource(
        string $customerUuid,
        RestCustomersResponseAttributesTransfer $restCustomersResponseAttributesTransfer,
        ?CustomerTransfer $customerTransfer = null
    ): RestResourceInterface;
}
