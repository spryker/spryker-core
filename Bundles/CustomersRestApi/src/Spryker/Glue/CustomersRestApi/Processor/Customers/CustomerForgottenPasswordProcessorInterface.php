<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CustomersRestApi\Processor\Customers;

use Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface CustomerForgottenPasswordProcessorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function sendPasswordRestoreMail(
        RestCustomerForgottenPasswordAttributesTransfer $restCustomerForgottenPasswordAttributesTransfer
    ): RestResponseInterface;
}
