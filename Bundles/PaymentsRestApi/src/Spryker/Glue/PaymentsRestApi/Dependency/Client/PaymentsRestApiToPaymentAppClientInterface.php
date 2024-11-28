<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Dependency\Client;

use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;

interface PaymentsRestApiToPaymentAppClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    public function getCustomer(PaymentCustomerRequestTransfer $paymentCustomerRequestTransfer): PaymentCustomerResponseTransfer;
}
