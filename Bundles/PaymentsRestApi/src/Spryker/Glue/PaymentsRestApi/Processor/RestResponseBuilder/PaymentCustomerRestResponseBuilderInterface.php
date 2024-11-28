<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface PaymentCustomerRestResponseBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerResponseTransfer $paymentCustomerResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createPaymentCustomersRestResponse(
        PaymentCustomerResponseTransfer $paymentCustomerResponseTransfer
    ): RestResponseInterface;
}
