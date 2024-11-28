<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PaymentsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\RestPaymentCustomersResponseAttributesTransfer;

interface PaymentCustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\PaymentCustomerResponseTransfer $paymentCustomerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\RestPaymentCustomersResponseAttributesTransfer
     */
    public function mapPaymentCustomerResponseTransferToRestPaymentCustomersResponseAttributesTransfer(
        PaymentCustomerResponseTransfer $paymentCustomerResponseTransfer
    ): RestPaymentCustomersResponseAttributesTransfer;
}
